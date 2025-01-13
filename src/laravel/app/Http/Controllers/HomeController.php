<?php

namespace App\Http\Controllers;

use App\Helpers\ReadabilityHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $read = new ReadabilityHelper('Italy, country of south-central Europe, occupying a peninsula that juts deep into the Mediterranean Sea. Italy comprises some of the most varied and scenic landscapes on Earth and is often described as a country shaped like a boot. At its broad top stand the Alps, which are among the world’s most rugged mountains. Italy’s highest points are along Monte Rosa, which peaks in Switzerland, and along Mont Blanc, which peaks in France. The western Alps overlook a landscape of Alpine lakes and glacier-carved valleys that stretch down to the Po River and the Piedmont. Tuscany, to the south of the cisalpine region, is perhaps the country’s best-known region. From the central Alps, running down the length of the country, radiates the tall Apennine Range, which widens near Rome to cover nearly the entire width of the Italian peninsula. South of Rome the Apennines narrow and are flanked by two wide coastal plains, one facing the Tyrrhenian Sea and the other the Adriatic Sea. Much of the lower Apennine chain is near-wilderness, hosting a wide range of species rarely seen elsewhere in western Europe, such as wild boars, wolves, asps, and bears. The southern Apennines are also tectonically unstable, with several active volcanoes, including Vesuvius, which from time to time belches ash and steam into the air above Naples and its island-strewn bay. At the bottom of the country, in the Mediterranean Sea, lie the islands of Sicily and Sardinia. Italy’s political geography has been conditioned by this rugged landscape. With few direct roads between them, and with passage from one point to another traditionally difficult, Italy’s towns and cities have a history of self-sufficiency, independence, and mutual mistrust. Visitors today remark on how unlike one town is from the next, on the marked differences in cuisine and dialect, and on the many subtle divergences that make Italy seem less a single nation than a collection of culturally related points in an uncommonly pleasing setting.');
        $read->getResult();
        return view('home');
    }
}
