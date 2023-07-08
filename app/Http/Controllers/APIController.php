<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


/**
 *
 */
class APIController extends Controller
{
    /**
     * @var string
     */
    private $episodeUrl;

    /**
     * @var string
     */
    private $characterUrl;

    /**
     * @var string[]
     */
    private $filterList;

    public function __construct()
    {
        $this->episodeUrl = env('API_URL') . 'episode';
        $this->characterUrl = env('API_URL') . 'character';
        $this->filterList = ['alive',
                            'dead',
                            'human',
                            'alien',
                            'humanoid',
                            'animal',
                            'female',
                            'male'];
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getCharacters()
    {
        $url = $this->characterUrl;
        $response = Http::get($url, []);

        $data = $response->json();
        $data['nextPageUrl'] = '/characterslist/page/2';
        $data['prevPageUrl'] = '/characterslist/page/1';

        $episodeList = $this->getEpisodes();

        foreach($data['results'] as $key => $val) {
            $episodeKey = array_search($val['episode'][0], array_column($episodeList, 'url'));
            $data['results'][$key]['episode']['name'] = $episodeList[$episodeKey]['name'];
        }

        return view('character-list', ['data' => $data]);
    }


    /**
     * @param Request $request
     * @param $slug
     * @param $pagenum
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getPage(Request $request, $slug, $pagenum)
    {
        $page = (int)$pagenum;
        $url = $this->characterUrl;

        if(!isset($page) || !is_numeric($page)) {
            $page = 1;
        }

        $response = Http::get($url, ['page' => $page]);

        $data = $response->json();

        $episodeList = $this->getEpisodes();

        foreach($data['results'] as $key => $val) {
            $episodeKey = array_search($val['episode'][0], array_column($episodeList, 'url'));
            $data['results'][$key]['episode']['name'] = $episodeList[$episodeKey]['name'];
        }

        $data['nextPageUrl'] = '/characterslist/page/'.$page+1;
        $data['prevPageUrl'] = '/characterslist/page/'.$page-1;

        return view('character-list', ['data' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function filterData(Request $request)
    {
        $inputVars = $request->input();
        foreach($inputVars as $key => $val) {
            if($val != '') {
                if($key != 'name' && in_array($val,$this->filterList)) {
                    $filters[$key] = $val;
                }
            }
        }

        if(isset($inputVars['name'])) {
            $filters['name'] = htmlspecialchars($inputVars['name']);
        }

        if(!isset($filters['page']) || !is_numeric($filters['page'])) {
            $filters['page'] = 1;
        }

        $response = Http::get($this->characterUrl, $filters);
        $data = $response->json();

        $data['nextPageUrl'] = $request->url().'?page='.$filters['page'] + 1;
        $data['prevPageUrl'] = $request->url().'?page='.$filters['page'] - 1;
        unset($filters['page']);

        foreach($filters as $key=>$val) {
            $data['nextPageUrl'] .= '&'.$key.'='.$val;
            $data['prevPageUrl'] .= '&'.$key.'='.$val;

        }

        $data['filters'] = $filters;
        $episodeList = $this->getEpisodes();

        foreach($data['results'] as $key => $val) {
            $episodeKey = array_search($val['episode'][0], array_column($episodeList, 'url'));
            $data['results'][$key]['episode']['name'] = $episodeList[$episodeKey]['name'];
        }

        return view('character-list', ['data' => $data]);
    }


    /**
     * This function is to get a full list of the episodes since the APi does not allow you get
     * the full result set in one call.
     *
     * @return array|mixed
     *
     */
    protected function getEpisodes()
    {
        $response = Http::get($this->episodeUrl, []);
        $data = $response->json();
        $pages = $data['info']['pages'];
        $episodesList = $data['results'];

        if($pages > 1) {
            for($i=2;$i <= $pages;$i++) {
                $response = Http::get($this->episodeUrl, ['page' => $i]);
                $data = $response->json();
                $episodesList = array_merge($episodesList,$data['results']);
            }
        }

        return $episodesList;
    }

}
