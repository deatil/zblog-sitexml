<?php

/**
 * 生成 sitemap.xml 文件
 */
class Sitemap
{
    private $config = [
        'encoding' => 'utf-8',
        'version' => '1.0',
    ];
    
    private $content = '';
    
    private $items = [];

    public function __get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        
        return null;
    }

    public function __set($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    public function getContent()
    {
        if (empty($this->content)) {
            $this->build();
        }

        return $this->content;
    }

    /**
     * $changefreq 
     *  always 经常
     *  hourly 每小时
     *  daily 每天
     *  weekly 每周
     *  monthly 每月
     *  yearly 每年
     *  never 从不
     */
    public function addItem(
        $loc, 
        $time = 0,
        $changefreq = 'daily', 
        $priority = 2
    ) {
        $arr = array(
            '1.0',
            '0.9',
            '0.8',
            '0.7',
            '0.6',
            '0.5',
        );
        
        $this->items[] = array(
            'loc' => $loc,
            'priority' => isset($arr[$priority]) ? $arr[$priority] : $priority,
            'lastmod' => $time ? (is_numeric($time) ? date('Y-m-d H:i:s', $time) : $time) : date('Y-m-d H:i:s', time()),
            'changefreq' => $changefreq,
        );
        
        return $this;
    }

    
    /**
     * 生成sitemap.xml文件内容
     */
    public function build()
    {
        $s = "<?xml version='{$this->config['version']}' encoding='{$this->config['encoding']}'?>\r\n";
        /* $s .= "<?xml-stylesheet type='text/xsl' href='sitemap.xsl'?>\r\n";*/
        $s .= "\t<urlset>\r\n";
        
        foreach ($this->items as $item) {
            $s .= "\t\t<url>\n";
            $s .= "\t\t\t<loc>{$item['loc']}</loc>\r\n";
            $s .= "\t\t\t<lastmod>{$item['lastmod']}</lastmod>\r\n";
            $s .= "\t\t\t<changefreq>{$item['changefreq']}</changefreq>\r\n";
            $s .= "\t\t\t<priority>{$item['priority']}</priority>\r\n";
            $s .= "\t\t</url>\n";
        }
        
        $s .= "\t</urlset>";
        $this->content = $s;
        
        return $this;
    }

    /**
     * 将产生的sitemap内容直接打印输出
     */
    public function show()
    {
        $content = $this->getContent();

        header("Content-Type: text/xml; charset=utf-8");
        exit($content);
    }

    /**
     * 将产生的sitemap 内容保存到文件
     */
    public function saveToFile($fname)
    {
        if (! file_exists($fname)) {
            return false;
        }
        
        $content = $this->getContent();

        $handle = fopen($fname, 'w+');
        if ($handle === false) {
            return false;
        }

        fwrite($handle, $content);
        fclose($handle);
    }

    /**
     * 从文件中获取输出
     */
    public function getFile($fname)
    {
        $handle = fopen($fname, 'r');
        if ($handle === false) {
            return false;
        }

        $data = '';
        while (!feof($handle)) {
            $data .= fgets($handle);
        }
        
        fclose($handle);
        
        return $data;
    }

}
