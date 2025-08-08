<?php
class ModelExtensionModuleDatabaseOptimizer extends Model {
    public function optimizeTables() {
        $tables = $this->db->query("SHOW TABLES");
        $optimized_tables = array();
        foreach ($tables->rows as $table) {
            $table_name = array_values($table)[0];
            $this->db->query("OPTIMIZE TABLE `" . $table_name . "`");
            $optimized_tables[] = $table_name;
        }
        return $optimized_tables;
    }

    public function clearSessions() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE `expire` < NOW()");
    }

    public function clearCache() {
        $files = glob(DIR_CACHE . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function checkTables() {
        $tables = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "%'");
        $results = array();
        foreach ($tables->rows as $table) {
            $table_name = array_values($table)[0];
            $result = $this->db->query("CHECK TABLE `" . $table_name . "`");
            $status = $result->row['Msg_text'];
            $repaired = false;
            if ($status != 'OK') {
                $this->db->query("REPAIR TABLE `" . $table_name . "`");
                $repaired = true;
            }
            $results[] = array(
                'table' => $table_name,
                'status' => $status,
                'repaired' => $repaired
            );
        }
        return $results;
    }
}