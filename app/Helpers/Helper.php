<?php
namespace App\Helpers;
use DOMDocument;
use DB;
use Mail;
use Session;
use Redirect;
use App\Estimation;
use PDF;
use App\Models\User;




class GlobalFunctions {

    //Generates 12 characters unique code string
    public static function generateRef()
    {
        $lengths = 12;
        return $randomStrings = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $lengths);
    }

    //function to send mail
    public static function sendmail($from,$fromname,$to,$toname,$subject,$data,$mailview,$cc=null,$attachment=null)
        {

            $response = Mail::send($mailview, $data, function($message) use ($from,$fromname,$to,$toname,$subject,$attachment,$cc)
            {
                $message->from($from,$fromname);
                $message->to($to,$toname);
                if($cc)
                {
                    $message->cc($cc);
                }
                $message->bcc('info@faruhi.com');
                $message->subject($subject);
              if($attachment != '')
                $message->attach($attachment);
            });
            if(Mail::failures())
            {
                $response = 0;
            }
            else
          {
              $response = 1;
            }
                return $response;
      }


      public static function test($id)
      {
          $driver = Driver::where('userId',$id)->first();
          return $driver;
      }


      public static function getpos($accountId,$date)
      {
        $date = date("Y-m-d", strtotime($date));
        return $detail = Pos::where('accountId',$accountId)->whereDate('date',$date)->first();

      }

      public static function getMonthWiseTotal($month,$year,$badgeId)
      {
        $date = date("Y-m-d", strtotime($date));
        return $detail = Trip::where('badgeId',$accountId)->whereMonth('date',$month)->whereYear('date',$year)->sum('total');
      }

      public static function getdriverDetail($badgeId)
      {
        $driver = User::where('badgeId',$badgeId)->first();
        return $driver;
      }

      public static function rotationCity($badgeId,$taxiId)
      {
        $detail = Rotation::where(array("badgeId"=>$badgeId,'taxiId'=>$taxiId))->orderby('id','Desc')->first();
        if(!empty($detail))
        {
        $driver = RotationCities::where('id',$detail->rotationCityId)->first();
        return $driver;
        }
      }

      public static function RotationCategories($id)
      {
        $driver = RotationCategories::where('id',$id)->first();
        return $driver;
      }

      public static function Rotationdetail($badgeId,$taxiId)
      {
        $driver = Rotation::where(array("badgeId"=>$badgeId,'taxiId'=>$taxiId))->orderby('id','Desc')->first();
        return $driver;
      }

      public static function vdetail($taxiId)
      {
        $driver = Vehicles::where(array('id'=>$taxiId))->first();
        return $driver;
      }

      public static function totalMarks($taxiId,$id,$cat)
      {
        $driver = Rotation::where(array('taxiId'=>$taxiId,"rotationCategoryId"=>$cat))->where('id','<=',$id)->sum('marks');
        return $driver;
      }
      public static function DrivertotalMarks($taxiId,$id,$cat,$badgeId)
      {
        $driver = Rotation::where(array('taxiId'=>$taxiId,"rotationCategoryId"=>$cat,"badgeId"=>$badgeId))->where('id','<=',$id)->sum('marks');
        return $driver;
      }

      public static function historytotalMarks($taxiId,$id,$cat)
      {
        $driver = RotationHistory::where(array('taxiId'=>$taxiId,"rotationCategoryId"=>$cat))->where('id','<=',$id)->sum('marks');
        return $driver;
      }

      public static function ownerdetail($ownerId)
      {
        $driver = OwnerTaxi::with('userdetail','taxidetail')->where('accountId',$ownerId)->first();
        return $driver;
      }

      public static function postotal($month,$year,$id)
      {
        return $detail = Pos::where('accountId',$id)->whereMonth('date',$month)->whereYear('date',$year)->sum('amount');

      }

      public static function getenvelope($month,$year,$ownerId)
      {
        $driver = Envelope::with('detail')->where('accountId',$ownerId)->whereHas('detail',function($w) use($year){
         $w->where('batchYear',$year);
       })->whereHas('detail',function($w)use($month){
         $w->where('batchMonth',$month);
        })->get();
        return $driver;

      }

      public static function getClaimTrip($id)
      {
        $trip = Trip::where(array('envelopeId'=>$id))->sum('total');
        return $trip;
      }

      public static function getenvelopeClaim($month,$year,$ownerId)
      {
        $driver = Envelope::with('detail')->where('accountId',$ownerId)->whereHas('detail',function($w) use($year){
         $w->where('batchYear',$year);
       })->whereHas('detail',function($w)use($month){
         $w->where('batchMonth',$month);
        })->get();
        $a = [];
        if(!empty($driver))
        {
          foreach($driver as $d)
          {
            $a[] = $d->id;
          }
        }


        return $trip = Trip::whereIn('envelopeId',$a)->sum('total');
      }

      public static function getenvelopeClaimYearly($year,$ownerId)
      {
        $driver = Envelope::with('detail')->where('accountId',$ownerId)->whereHas('detail',function($w) use($year){
         $w->where('batchYear',$year);
       })->get();
        $a = [];
        if(!empty($driver))
        {
          foreach($driver as $d)
          {
            $a[] = $d->id;
          }
        }

        return $trip = Trip::whereIn('envelopeId',$a)->sum('total');
      }

      public static function totalPos($accountId,$month,$year)
      {
        $total = Pos::where(array('accountId'=>$accountId))->whereYear('date',$year)->whereMonth('date',$month)->sum('amount');
        return $total;
      }

      public static function getClaimTaxiSaver($month,$year,$ownerId)
      {
        $driver = Envelope::with('detail')->where('accountId',$ownerId)->whereHas('detail',function($w) use($year,$month){
         $w->where('batchYear',$year);
         $w->where('batchMonth',$month);
       })->get();
        $a = [];
        if(!empty($driver))
        {
          foreach($driver as $d)
          {
            $a[] = $d->id;
          }
        }

        return $claimtaxiSaverAmount = TaxiSaver::whereIn('envelopeId',$a)->sum('amount');
      }

      public static function getClaimTaxiSaverYearly($year,$ownerId)
      {
        $driver = Envelope::with('detail')->where('accountId',$ownerId)->whereHas('detail',function($w) use($year){
         $w->where('batchYear',$year);
       })->get();
        $a = [];
        if(!empty($driver))
        {
          foreach($driver as $d)
          {
            $a[] = $d->id;
          }
        }

        return $taxisaverAmount = TaxiSaver::whereIn('envelopeId',$a)->sum('amount');
      }


      public static function getTaxiSaverEnvelopeId($id)
      {
        return $taxisaverAmount = TaxiSaver::where('envelopeId',$id)->sum('amount');
      }

      public static function debitPos($date,$accountId)
      {
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        return $taxisaverAmount = Pos::where('accountId',$accountId)->where('type','debit')->whereYear('date',$year)->whereMonth('date',$month)->sum('amount');
      }












}
