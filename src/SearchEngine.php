<?php

class SearchEngine
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addSite($site)
    {
        if ($this->checkIfSiteExist($site['url'])) {
            return false;
        }

        $this->db->query("INSERT INTO sites (title, blurb, keywords, url) VALUES (:title, :blurb, :keywords, :url)");

        $this->db->bind(":title", $site['title'], PDO::PARAM_STR);
        $this->db->bind(":blurb", $site['blurb'], PDO::PARAM_STR);
        $this->db->bind(":keywords", $site['keywords'], PDO::PARAM_STR);
        $this->db->bind(":url", $site['url'], PDO::PARAM_STR);

        return $this->db->execute();
    }

    public function addSites($sites)
    {
        foreach ($sites as $site) {
            if ($this->checkIfSiteExist($site['url'])) {
                return false;
            }

            $this->db->query("INSERT INTO sites (title, blurb, keywords, url) VALUES (:title, :blurb, :keywords, :url)");

            $this->db->bind(":title", $site['title'], PDO::PARAM_STR);
            $this->db->bind(":blurb", $site['blurb'], PDO::PARAM_STR);
            $this->db->bind(":keywords", $site['keywords'], PDO::PARAM_STR);
            $this->db->bind(":url", $site['url'], PDO::PARAM_STR);

            return $this->db->execute();
        }
    }

    public function findSites($name, $page)
    {
        $results_per_page = 10;
        $page_first_result = ($page - 1) * $results_per_page;

        $this->db->query("SELECT * FROM sites WHERE title LIKE :name OR keywords LIKE :name OR blurb LIKE :name OR url LIKE :name");

        $this->db->bind(':name', "%{$name}%", PDO::PARAM_STR);

        $this->db->execute();

        $number_of_records = $this->db->rowCount();

        $number_of_page = ceil($number_of_records / $results_per_page);

        $this->db->query("SELECT * FROM sites WHERE title LIKE :name OR keywords LIKE :name OR blurb LIKE :name OR url LIKE :name LIMIT $page_first_result, $results_per_page");

        $this->db->bind(':name', "%{$name}%", PDO::PARAM_STR);

        $this->db->execute();

        return ['results' => $this->db->resultset(), 'count' => $number_of_records, 'pages' => $number_of_page];
    }

    public function checkIfSiteExist($url)
    {
        $this->db->query("SELECT * FROM sites WHERE url = :url");

        $this->db->bind(":url", $url, PDO::PARAM_STR);

        $this->db->execute();

        return ($this->db->rowCount() > 0) ? true : false;
    }
}
