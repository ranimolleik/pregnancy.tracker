<?php
include("Database.php");

class Tracker {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get database connection
    public function getDb() {
        return $this->db;
    }

    // Get pregnancy progress
    public function getPregnancyProgress($mother_id) {
        $query = "SELECT pregnancy_start, pregnancy_week FROM mothers WHERE id = ?";
        $result = $this->db->read($query, [$mother_id]);
        
        if (!empty($result)) {
            $data = $result[0];
            $start_date = new DateTime($data['pregnancy_start']);
            $current_date = new DateTime();
            $interval = $start_date->diff($current_date);
            
            // Calculate weeks and days
            $total_days = $interval->days;
            $weeks = floor($total_days / 7);
            $days = $total_days % 7;
            
            // Calculate percentage (assuming 40 weeks is full term)
            $percentage = min(($weeks / 40) * 100, 100);
            
            return [
                'weeks' => $weeks,
                'days' => $days,
                'percentage' => $percentage,
                'start_date' => $data['pregnancy_start']
            ];
        }
        return null;
    }

    // Record sleep data
    public function recordSleep($mother_id, $hours, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $query = "INSERT INTO health_tracker (mother_id, sleep_hours, date) 
                 VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE sleep_hours = ?";
        
        return $this->db->save($query, [$mother_id, $hours, $date, $hours]);
    }

    // Record water intake
    public function recordWaterIntake($mother_id, $cups, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $query = "INSERT INTO health_tracker (mother_id, water_cups, date) 
                 VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE water_cups = ?";
        
        return $this->db->save($query, [$mother_id, $cups, $date, $cups]);
    }

    // Get sleep data
    public function getSleepData($mother_id) {
        $query = "SELECT sleep_hours FROM health_tracker WHERE mother_id = ? AND date = CURDATE()";
        $result = $this->db->read($query, [$mother_id]);
        return !empty($result) ? $result[0] : null;
    }

    // Get water intake
    public function getWaterIntake($mother_id) {
        $query = "SELECT water_cups FROM health_tracker WHERE mother_id = ? AND date = CURDATE()";
        $result = $this->db->read($query, [$mother_id]);
        return !empty($result) ? $result[0] : null;
    }

    // Get weekly summary
    public function getWeeklySummary($mother_id) {
        $query = "SELECT 
                    AVG(sleep_hours) as avg_sleep_hours,
                    SUM(water_cups) as total_water_cups
                 FROM health_tracker 
                 WHERE mother_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $result = $this->db->read($query, [$mother_id]);
        return !empty($result) ? $result[0] : null;
    }

    // Get weekly advice
    public function getWeeklyAdvice($week) {
        $query = "SELECT advice FROM weekly_advice WHERE pregnancy_week = ?";
        $result = $this->db->read($query, [$week]);
        return !empty($result) ? $result[0]['advice'] : null;
    }

    // Get weekly meals
    public function getWeeklyMeals($week, $complication) {
        $query = "SELECT * FROM meals WHERE week = ? AND complication = ?";
        $result = $this->db->read($query, [$week, $complication]);
        return $result;
    }

    // Get weekly exercises
    public function getWeeklyExercises($week, $complication) {
        $query = "SELECT * FROM exercises WHERE week = ? AND complication = ?";
        $result = $this->db->read($query, [$week, $complication]);
        return $result;
    }
}
?> 