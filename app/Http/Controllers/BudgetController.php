<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Http\Request;
use App\Http\Requests;

class BudgetController extends Controller
{
    public function showForm()
    {
        return view('upload');
    }
    public function store(Request $request)
    {   
        //get file
        $upload=$request->file('upload-file');
        $filePath=$upload->getRealPath(); //get the real path from the computer
        //open and read
        $file=fopen($filePath, 'r');

        $header= fgetcsv($file);

        // dd($header);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            //first make lower case header
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }

        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
            {
                continue;
            }
            //trim data
            foreach ($columns as $key => &$value) {
                $value=preg_replace('/\D/','',$value);
            }

           $data= array_combine($escapedHeader, $columns);

           // setting type
           foreach ($data as $key => &$value) {
            $value=($key=="zip" || $key=="month")?(integer)$value: (float)$value;
           }

           // Table update
           $zip=$data['zip'];
           $month=$data['month'];
           $rent=$data['rent'];
           $food=$data['food'];
           $medical=$data['medical'];
           $other=$data['other'];
           $sector=$data['sector'];

           $budget= Budget::firstOrNew(['zip'=>$zip,'month'=>$month]);
           $budget->rent=$rent;
           $budget->food=$food;
           $budget->medical=$medical;
           $budget->other=$other;
           $budget->sector=$sector;
           $budget->save();
        }
        
        
    }
}