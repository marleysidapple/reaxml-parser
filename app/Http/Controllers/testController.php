<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Reaxml;

class testController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$rootPath = base_path('reaxml');
		$allData = Reaxml::parseXml($rootPath);

		print_r($allData);
	}

}
