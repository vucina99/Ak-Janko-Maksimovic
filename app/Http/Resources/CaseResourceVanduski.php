<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CaseResourceVanduski extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->date_of_reporting_to_insurance){
            $dateOfReporting = Carbon::parse($this->date_of_reporting_to_insurance);
            $today = Carbon::now();
            $differenceInDays = $dateOfReporting->diffInDays($today);
            if (!$dateOfReporting->isPast()) {
                $differenceInDays = 0;;
            }
        }else{
            $differenceInDays = null;
        }

        return [
            'id' => $this->id,
            'number_office' => $this->number_office,
            'number_institution' => $this->number_institution,
            'prosecutor' => $this->prosecutor,
            'defendants' => $this->defendants,
            'mark' => $this->mark,
            'fail_day' =>  !$this->fail_day ? null :  Carbon::parse($this->fail_day)->format("d-m-Y"),
            'archive' => $this->archive,
            'brand' => $this->brand,
            'date_send_to_expert' => !$this->date_send_to_expert ? null : Carbon::parse($this->date_send_to_expert)->format("d-m-Y"),
            'date_send_to_mail' => !$this->date_send_to_mail  ? null : Carbon::parse($this->date_send_to_mail)->format("d-m-Y"),
            'date_of_findings' => !$this->date_of_findings  ? null : Carbon::parse($this->date_of_findings)->format("d-m-Y"),
            'date_of_reporting_to_insurance' => !$this->date_of_reporting_to_insurance  ? null : Carbon::parse($this->date_of_reporting_to_insurance)->format("d-m-Y"),
            'deadline' => $differenceInDays,
            'requested_amount' => $this->requested_amount,
            'paid_amount' => $this->paid_amount,
            'status' => $this->status,
            'mup_note' => $this->mup_note,
            'damage_number' => $this->damage_number,
            'commission' => $this->commission,
            'at' => $this->at,
            'expert_costs' => $this->expert_costs,
            'lawsuit' => $this->lawsuit,
            'note' => $this->note,
            'vansudski_number' => $this->vansudski_number,
            'createdAt' => $this->created_at
        ];
    }
}
