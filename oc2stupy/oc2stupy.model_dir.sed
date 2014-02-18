
s/class Model\([A-Z][^A-Z]*\)\([A-Z].*\)[ ]*extends Model/\nnamespace Stupycart\\Common\\Models\\\1;\n\nclass \2 extends \\Libs\\Stupy\\Model/g

s/this\->db\->query/this->db_query/g
s/this\->db\->escape/this->db_escape/g
s/this\->db\->getLastId/this->db_getLastId/g
