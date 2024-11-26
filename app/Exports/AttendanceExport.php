<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomProperties;

class AttendanceExport implements FromCollection, WithHeadings, WithStyles
{
    protected $month;
    protected $userId;
    protected $userName;

    public function __construct($month, $userId, $userName)
    {
        $this->month = $month;
        $this->userId = $userId;
        $this->userName = $userName;
    }

    public function collection()
    {
        $year = date('Y', strtotime($this->month));
        $month = date('m', strtotime($this->month));

        $attendanceRecords = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('user_id', $this->userId)
            ->orderBy('date', 'asc')
            ->get();

        if ($attendanceRecords->isEmpty()) {
            return collect([
                [
                    'Days' => 'No attendance records found',
                    'Time In AM' => '',
                    'Time Out AM' => '',
                    'Time In PM' => '',
                    'Time Out PM' => '',
                ],
            ]);
        }

        return $attendanceRecords->map(function ($attendance) {
            return [
                'Days' => $attendance->date ? (new \Carbon\Carbon($attendance->date))->day : 'N/A',
                'Time In AM' => $this->convertTo12HourFormat($attendance->time_in_am),
                'Time Out AM' => $this->convertTo12HourFormat($attendance->time_out_am),
                'Time In PM' => $this->convertTo12HourFormat($attendance->time_in_pm),
                'Time Out PM' => $this->convertTo12HourFormat($attendance->time_out_pm),
            ];
        });
    }

    public function headings(): array
    {
        return [
            [
                'Attendance Records for ' . \Carbon\Carbon::parse($this->month)->format('F Y'),
            ],
            [
                'Name: ' . $this->userName,
            ],
            // Table column headings
            [
                'Days', 'Time In AM', 'Time Out AM', 'Time In PM', 'Time Out PM',
            ],
        ];
    }

    function convertTo12HourFormat($time)
    {
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            $dateTime = \DateTime::createFromFormat('H:i:s', $time);
            return $dateTime->format('g:i A'); // Convert to 12-hour format with AM/PM
        }

        return '-';
    }

    // Custom styles for the PDF export
    public function styles($sheet)
    {

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16); // Title bold and large
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center-align title


        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12); // User name bold
        $sheet->mergeCells('A2:E2'); // Merge user name cells
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center-align user name

        // Table column headings (A3:E3)
        $sheet->getStyle('A3:E3')->getFont()->setBold(true); // Table headings bold
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center-align column headings

        // Table content (A4 onwards)
        $sheet->getStyle('A4:E' . ($sheet->getHighestRow()))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center-align table rows
        $sheet->getStyle('A4:E' . ($sheet->getHighestRow()))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Table borders

        // Adjusting column width
        $sheet->getColumnDimension('A')->setWidth(15); // Days column width
        $sheet->getColumnDimension('B')->setWidth(20); // Time In AM column width
        $sheet->getColumnDimension('C')->setWidth(20); // Time Out AM column width
        $sheet->getColumnDimension('D')->setWidth(20); // Time In PM column width
        $sheet->getColumnDimension('E')->setWidth(20); // Time Out PM column width
    }
}
