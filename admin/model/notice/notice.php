<?php
class ModelNoticeNotice extends Model {

    public function getNotice($notice_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "notice WHERE id = '" . (int)$notice_id . "'");

        return $query->row;
    }

    public function getNotices($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "notice";

        if (!empty($data['filter_title'])) {
            $sql .= " AND title LIKE '" . $this->db->escape((string)$data['filter_title']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
        }

        $sort_data = array(
            'title',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalNotices($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notice";

        $implode = array();

        if (!empty($data['filter_title'])) {
            $implode[] = "title LIKE '" . $this->db->escape((string)$data['filter_title']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}
