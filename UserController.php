<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    public function createc(Request $req)
    {
        $val=validator::make($req->all(),[
            'title'=>'required'
        ]);
        if($val->fails())
        {
            return $val->errors();
        }
        $data=DB::table('categories')->insert(['category_name'=>$req->title]);
        if($data)
        {
            return json_encode([
                'status'=>'001',
                'message'=>'success'
            ]);
        }
        else{
            return json_encode([
                'status'=>'002',
                'message'=>'failed'
            ]);
        }
        
    }
    public function updatec(Request $req)
    {
        $val=Validator::make($req->all(),[
            'title'=>'required',
        ]);
        if($val->fails())
        {
            return $val->errors();
        }
        $data=DB::table('categories')->where('id',$req->id)->update(['category_name'=>$req->title]);
        if($data)
        {
            
            return json_encode([
                'status'=>'001',
                'message'=>'successfuly updated',
            ]);
        }
        return json_encode([
            'status'=>'002',
            'message'=>'sorry',
        ]);
    }

    public function blogcreate(Request $req)
    {
        $val= Validator::make($req->all(),[
            'title'=>'required',
            'description'=>'required',
            'category_id'=>'required',
            'picture'=>'required'
        ]);
        if($val->fails())
        {
            return $val->errors();
        }
        if($req->has('picture')){
            $file= $req->file('picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('picture'), $filename); 
        }
        $data=DB::table('blogs')->insert(['title'=>$req->title,'description'=>$req->description,'tag'=>$req->tag,'category_id'=>$req->category_id,'picture'=>$filename]);
        if($data)
        {
            return json_encode([
                'status'=>'001',
                'message'=>'success',

            ]);
        }
        return json_encode([
            'status'=>'002',
            'massage'=>'failed'
        ]);
    }
    public function blogupdate(Request $req)
    {
        $val=Validator::make($req->all(),[
            'id'=>'required',
            'title'=>'required',
            'description'=>'required',
            'category_id'=>'required',
            'picture'=>'required'
        ]);

        if($val->fails())
        {
            return $val->errors();
        }
        if($req->has('picture')){
            $file= $req->file('picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('picture'), $filename); 
        }
        $check=DB::table('blogs')->where('id',$req->id)->update(['title'=>$req->title,'description'=>$req->description,'tag'=>$req->tag,'category_id'=>$req->category_id,'picture'=>$filename]);
        if($check)
        {
            return json_encode([
                'status'=>'001',
                'message'=>'successfully updated'
            ]);
        }
        return json_encode([
            'status'=>'002',
            'message'=>'cannot update'
        ]);
    }
    public function blogdel(Request $req)
    {
        $val=Validator::make($req->all(),[
        'id'=>'required',
        ]);
        if($val->fails())
        {
            return $val->errors();
        }
        $check=DB::table('blogs')->where('id',$req->id)->delete();
        
        if($check)
        {
            return json_encode([
                'status'=>'001',
                'massage'=>' successfully deleted',
            ]);
        }
        return json_encode([
            'status'=>'002',
            'message'=>'unable to delete',
        ]);
    }
    public function dataview()
    {
        $data=DB::table('blogs')->join('categories','categories.id','blogs.category_id')->get();
        if($data)
        {
            return json_encode([
                'status'=>'001',
                'message'=>'data',
                'data'=>$data,
            ]);

        }
        return json_encode([
            'status'=>'002',
            'message'=>'unable to view data',

        ]);
    }
    public function show(Request $req)
    {
        $val=Validator::make($req->all(),[
            'id'=>'required',
            ]);
            if($val->fails())
            {
                return $val->errors();
            }
        $check=DB::table('blogs')->where('id',$req->id)->first();
        if($check)
        {
            $data=DB::table('blogs')->where('category_id',$check->category_id)->get();
            if($data)
            {

                return json_encode([
                    'status'=>'001',
                    'message'=>'data',
                    'data'=>$check,
                    'related'=>$data

                ]);
    
            }
                return json_encode([
                'status'=>'002',
                'message'=>'unable to view data',
    
                ]);
        }
        return json_encode([
            'status'=>'002',
            'message'=>'unable to view data',

            ]);
        
    }

    public function search(Request $req)
    {
        $val=Validator::make($req->all(),[
            'title'=>'required',
        ]);
        if($val->fails())
        {
            return $val->errors();
        }
        $q=$req->input('title');
        $check=DB::table('blogs')->where('title','like', '%'.$q.'%');
        if($check->count()>0)
            {
                return json_encode([
                    'status'=>'001',
                    'message'=>'data',
                    'data'=>$check->get()
                ]);
    
            }
             return json_encode([
                'status'=>'002',
                'message'=>'unable to view data',
    
                ]);

    }
    public function time()
    {
        $now=carbon::now()->ToDateTimeString();
        $before=Carbon::now()->subDays('5')->ToDateTimeString();
        $after=Carbon::now()->addDays('2')->ToDateTimeString();
        $data=DB::table('blogs')->where('datetime','>=',$before)->where('datetime','<=',$after)->get();

        if($data)
        {
            return json_encode([
                'status'=>'001',
                'message'=>'sa',
                'data'=>$data
            ]);
        }

    }
}
