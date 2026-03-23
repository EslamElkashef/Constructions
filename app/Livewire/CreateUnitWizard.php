<?php

namespace App\Livewire;

use App\Models\Unit;
use App\Models\UnitType;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUnitWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    public $mode = 'create';

    public $unitId = null;

    // Step 1
    public $name;

    public $address;

    public $phone;

    public $employee_id;

    public $unit_type_id;

    public $status = 'available';

    public $city;

    public $sold_at;

    public $employee_name;

    public $unitTypes = [];

    // Step 2
    public $typeFields = [];

    public $details = [];

    // Step 3
    public $images = [];

    public $videos = [];

    // Step 4
    public $message;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($unitId = null)
    {
        $this->unitTypes = UnitType::all();
        $this->employee_id = auth()->id();
        $this->employee_name = auth()->user()->name ?? '';

        if ($unitId) {
            $unit = Unit::with(['details', 'media', 'employee'])->find($unitId);

            if (! $unit) {
                session()->flash('error', 'الوحدة غير موجودة.');

                return redirect()->route('units.index');
            }

            $this->mode = 'edit';
            $this->unitId = $unit->id;
            $this->name = $unit->name;
            $this->address = $unit->address;
            $this->phone = $unit->phone;
            $this->unit_type_id = $unit->unit_type_id;
            $this->status = $unit->status;
            $this->city = $unit->city;
            $this->sold_at = $unit->sold_at;
            $this->employee_id = $unit->employee_id;
            $this->employee_name = $unit->employee->name ?? '';

            // جلب الحقول الديناميكية
            if ($this->unit_type_id) {
                $this->updatedUnitTypeId($this->unit_type_id);
            }

            foreach ($unit->details as $d) {
                $this->details[$d->field] = $d->value;
            }

            // لو الأعمدة الأساسية مش موجودة في جدول units، نجيبها من details
            $this->details['type'] = $unit->type ?? ($this->details['type'] ?? null);
            $this->details['area'] = $unit->area ?? ($this->details['area'] ?? null);
            $this->details['price'] = $unit->price ?? ($this->details['price'] ?? null);

            $this->images = $unit->media->where('type', 'image')->pluck('path')->toArray();
            $this->videos = $unit->media->where('type', 'video')->pluck('path')->toArray();
        }
    }

    public function updatedUnitTypeId($value)
    {
        $type = UnitType::find($value);
        if (! $type) {
            $this->typeFields = [];

            return;
        }

        $fields = is_string($type->fields) ? json_decode($type->fields, true) : $type->fields;
        $this->typeFields = is_array($fields) ? $fields : [];

        foreach ($this->typeFields as $f) {
            if (! isset($this->details[$f['name']])) {
                $this->details[$f['name']] = $f['type'] === 'checkbox' ? false : null;
            }
        }
    }

    public function updatedStatus($value)
    {
        if ($value === 'sold' && empty($this->sold_at)) {
            $this->sold_at = now()->format('Y-m-d');
        } elseif ($value !== 'sold') {
            $this->sold_at = null;
        }
    }

    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateStep($step)
    {
        if ($step == 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'unit_type_id' => 'required|integer|exists:unit_types,id',
                'status' => 'required|in:available,reserved,sold',
                'city' => 'required|string',
                'sold_at' => 'nullable|date',
            ]);
        }

        if ($step == 2 && ! empty($this->typeFields)) {
            $rules = [];
            foreach ($this->typeFields as $f) {
                $rules['details.'.$f['name']] = $f['rules'] ?? 'nullable';
            }
            $this->validate($rules);
        }

        if ($step == 3) {
            $this->validate([
                'images.*' => 'nullable|image|max:5120',
                'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200',
            ]);
        }
    }

    public function submit()
    {
        $this->validateStep(1);
        $this->validateStep(2);
        $this->validateStep(3);

        $data = [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'employee_id' => $this->employee_id,
            'unit_type_id' => $this->unit_type_id,
            'status' => $this->status,
            'city' => $this->city,
            'sold_at' => $this->sold_at,
            // لو الأعمدة الأساسية موجودة في details، نضيفها
            'type' => $this->details['type'] ?? null,
            'area' => $this->details['area'] ?? null,
            'price' => $this->details['price'] ?? null,
        ];

        $unit = $this->mode === 'edit'
            ? tap(Unit::findOrFail($this->unitId))->update($data)
            : Unit::create($data);

        foreach ($this->details as $key => $value) {
            $unit->details()->updateOrCreate(
                ['field' => $key],
                ['value' => $value, 'field_label' => ucfirst(str_replace('_', ' ', $key))]
            );
        }

        foreach ($this->images as $image) {
            if (is_string($image)) {
                continue;
            }
            $path = $image->store('units/images', 'public');
            $unit->media()->create(['type' => 'image', 'path' => $path]);
        }

        foreach ($this->videos as $video) {
            if (is_string($video)) {
                continue;
            }
            $path = $video->store('units/videos', 'public');
            $unit->media()->create(['type' => 'video', 'path' => $path]);
        }

        $this->message = $this->mode === 'edit'
            ? 'Unit Updated Successfully✅'
            : 'Unit Created Successfully🎉';

        $this->dispatch('unit-saved', message: $this->message);

        return redirect()->route('units.index');
    }

    public function render()
    {
        return view('livewire.create-unit-wizard')->layout('layouts.master');
    }
}
