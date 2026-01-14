<?php

namespace App\Controllers;

use App\Core\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        $sql = "SELECT * FROM calendar_events 
                WHERE is_public = 1 
                ORDER BY start_date ASC";
        
        $events = $this->db->fetchAll($sql);
        
        $this->view('calendar.index', ['events' => $events]);
    }
}
