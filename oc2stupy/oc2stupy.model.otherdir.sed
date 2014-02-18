s/this\->db\->query/this->ocdb->db_query/g
s/this\->db\->escape/this->ocdb->db_escape/g
s/this\->db\->getLastId/this->ocdb->db_getLastId/g


s/index.php?route=\([^\/]*\/[^\/]*\/[^\/]*\)\&/\1?/g
s/index.php?route=\([^\/]*\/[^\/]*\)\&/\1?/g
s/index.php?route=\([^\/]*\)\&/\1?/g
s/index.php?route=//g
