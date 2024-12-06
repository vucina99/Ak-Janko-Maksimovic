<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TrialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numberOffice' => $this->number_office,
            'numberInstitution' => $this->number_institution,
            'user' => new UserResource($this->user),
            'prosecutor' => $this->prosecutor,
            'defendants' => $this->defendants,
            'institution' => new InstitutionResource($this->institution),
            'note' => $this->note,
            'date' => $this->date,
            'dateParse' => $this->date ? Carbon::parse($this->date)->format("d-m-Y") : $this->date,
            'time' => $this->time,
            'archive' => $this->archive,
            'courtroom_number' => $this->courtroom_number,
            'isFinished' => $this->is_finished,


        ];
    }
}
