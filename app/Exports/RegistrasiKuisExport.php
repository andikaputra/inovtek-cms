<?php

namespace App\Exports;

use App\Models\QuizRegistration;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrasiKuisExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles, WithTitle
{
    protected $number;

    protected $range_date;

    protected $village_id;

    protected $search;

    protected $id_kuis;

    public function __construct(string $id_kuis, ?string $range_date = null, ?string $village_id = null, ?string $search = null)
    {
        $this->range_date = $range_date;
        $this->village_id = $village_id;
        $this->search = $search;
        $this->id_kuis = $id_kuis;
    }

    public function collection()
    {
        $query = QuizRegistration::query();
        $query->with('regionDetail');
        $query->where('quiz_link_id', $this->id_kuis);

        if (isset($this->search)) {
            $query->where(function ($query) {
                $lowerKeyword = strtolower($this->search);
                $query->whereRaw('LOWER(email) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(phone_no) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        }

        if (isset($this->range_date)) {
            $dates = explode(' to ', $this->range_date);
            if (count($dates) === 2) {
                $start_date = $dates[0].' 00:00:00';
                $end_date = $dates[1].' 23:59:59';

                $query->whereBetween('created_at', [$start_date, $end_date]);
            } elseif (count($dates) === 1) {
                $date = $dates[0];

                $query->whereDate('created_at', $date);
            }
        }

        if (isset($this->village_id)) {
            if ($this->village_id != 'semua') {
                $query->where('region_detail_id', $this->village_id);
            }
        }

        $items = $query->get()->map(function ($item) {
            $printItem['name'] = $item->name;
            $printItem['email'] = $item->email;
            $printItem['phone_no'] = "'".$item->phone_no;
            $printItem['village'] = $item->regionDetail?->village;
            $printItem['sex_type'] = $item->sex_type;
            $printItem['age'] = $item->age;
            $printItem['work'] = $item->work;
            $printItem['quiz_code'] = $item->quiz_code;
            $printItem['created_at'] = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');

            return $printItem;
        });

        $items = $items->map(function ($item, $index) {
            return array_merge(['No' => $index + 1], $item);
        });

        return $items;
    }

    public function title(): string
    {
        return 'Data Registrasi Kuis';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'No. Telp',
            'Nama Desa',
            'Jenis Kelamin',
            'Umur',
            'Pekerjaan',
            'Kode Kuis',
            'Tanggal Pendaftaran',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $lastCell = $highestColumn.$highestRow;
                $rangeCell = 'A1:'.$lastCell;
                $event->sheet->getDelegate()->getStyle($rangeCell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
