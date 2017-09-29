<?php

namespace App\Console\Commands;

use App\Email;
use App\Link;
use App\Page;
use Illuminate\Console\Command;

class CrawlPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pages crawled getting email and domains saving on db';

    /**
     * @var Page
     */
    protected $page;


    private $content;

    /**
     * CrawlPages constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        parent::__construct();

        $this->page = $page;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pages = $this->page->where("crawled", "0")->get();

        if(count($pages) > 0) {
            if ($this->confirm("Are you sure to crawl " . count($pages) . " pages? [Y|n]", true)) {
                $this->info("Crawling " . count($pages) . " pages ...");

                $this->crawl($pages);

                return true;
            }

            return false;
        }

        $this->error("Nothing to crawl");
    }

    /**
     * Do the magic
     * @param $pages
     */
    private function crawl($pages)
    {
        $errors = [];

        $this->info("Starting crawler for  " . count($pages) . " pages.");

        $bar = $this->output->createProgressBar(count($pages));

        foreach ($pages as $page) {

            try {
                $content = file_get_contents($page->url);
            } catch (\Exception $e){
                $errors[] = "Could not access page $page->url \n Error: " .$e->getMessage();
                continue;
            }

            $this->hydrate($content);
            $this->getEmails();
            $this->getLinks();
            $this->updatePage($page->url);

            $bar->advance();
        }

        $bar->finish();

        echo "\n";

        if(count($errors)) {
            foreach ($errors as $error) {
                $this->error($error);
            }
        }

    }

    /**
     * Remove all html tags
     * @param $content
     */
    private function hydrate($content)
    {
        $this->content = strip_tags($content, "<a>");
    }

    /**
     * Get all emails from the content and adds to DB
     * @return bool
     */
    private function getEmails()
    {
        $data = [];
        preg_match_all('(([-_.\w]+@[a-zA-Z0-9_]+?\.[a-zA-Z0-9]{2,6}))', $this->content, $result);
        preg_match_all('(\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3})', $this->content, $result2);

        $emails = array_merge($result[0], $result2[0]);
        foreach ($emails as $email) {

            if(
                is_null(\DB::table('emails')->where('email', '=', $email)->first(['id'])) &&
                !in_array(['email' => $email], $data)
            ) {
                $data[] = ['email' => $email];
            }
        }

        if(count($data)) {
            Email::insert($data);
        }

        return true;
    }

    /**
     * Get links from the content and adds to DB
     * @return bool
     */
    private function getLinks()
    {
        $data = [];

        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $this->content, $result);

        foreach ($result['href'] as $link) {

            if(
                filter_var($link, FILTER_VALIDATE_URL) &&
                is_null(\DB::table('links')->where('url', '=', $link)->first(['id'])) &&
                !in_array(['url' => utf8_decode($link)], $data)
            ) {
                $data[] = ['url' => utf8_decode($link)];
            }

            continue;
        }

        if(count($data)) {
            Link::insert($data);
        }

        return true;
    }

    /**
     * Set page property crawled as true
     * @param $page
     * @return bool
     */
    private function updatePage($page)
    {
        $pageCrawled = Page::where("url", $page)->first();

        $pageCrawled->crawled = 1;
        $pageCrawled->save();

        return true;
    }
}
