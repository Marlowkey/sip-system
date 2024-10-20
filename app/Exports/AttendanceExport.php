<?php

namespace App\Exports;

use Log;
use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport implements FromCollection, WithHeadings
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
        Log::info('Exporting attendance for', ['month' => $this->month, 'user_id' => $this->userId]);

        $year = date('Y', strtotime($this->month));
        $month = date('m', strtotime($this->month));

        $attendanceRecords = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('user_id', $this->userId)
            ->with('user')
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

        Log::info('Attendance data fetched', ['data' => $attendanceRecords]);

        return $attendanceRecords->map(function ($attendance) {
            return [
                'Days' => $attendance->date ? (new Carbon($attendance->date))->day : 'N/A',
                'Time In AM' => $this->convertTo12HourFormat($attendance->time_in_am),
                'Time Out AM' => $this->convertTo12HourFormat($attendance->time_out_am),
                'Time In PM' => $this->convertTo12HourFormat($attendance->time_in_pm),
                'Time Out PM' => $this->convertTo12HourFormat($attendance->time_out_pm),
            ];
        });
    }


    public function headings(): array
    {
        $formattedMonth = Carbon::parse($this->month)->format('F Y');

        return [
            [
                'Attendance Records for ' . $formattedMonth
            ],
            [
                'Name: ' . $this->userName
            ],
            [
                'Days',
                'Time In AM',
                'Time Out AM',
                'Time In PM',
                'Time Out PM',
            ],
        ];
    }

    function convertTo12HourFormat($time) {
        // Check if the time is in HH:MM:SS format
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            $dateTime = DateTime::createFromFormat('H:i:s', $time);
            return $dateTime->format('g:i'); // Convert to 12-hour format without seconds
        }

        return '-';
    }




}
