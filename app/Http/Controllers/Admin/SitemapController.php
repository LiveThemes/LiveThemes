<?php

namespace App\Http\Controllers\Admin;

use App\Theme;
use App\ThemeColour;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use Str;

class SitemapController extends Controller
{
    public function index()
    {
    	$sitemap = Sitemap::create()

    				->add(Url::create('/')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/light/all/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/mid/all/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/dark/all/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/featured/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/oldest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/most-downloaded/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/least-downloaded/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/highest-rated/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/all/lowest-rated/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/red/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/orange/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/yellow/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/green/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/blue/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					)

					->add(Url::create('/browse/all/purple/newest/1')
						->setLastModificationDate(Carbon::yesterday())
						->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
						->setPriority(0.1)
					);

		$themes = Theme::select('id', 'updated_at', 'name')
							->where('public', 1)
							->orderBy('created_at')
							->get();

		foreach($themes as $t){
			$sitemap->add(Url::create( $t->url )
				->setLastModificationDate( $t->updated_at )
				->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
				->setPriority(0.1)
			);
		}

		$sitemap->writeToFile( public_path('sitemap.xml') );
    }
}
