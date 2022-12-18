<?php

namespace App\Http\Controllers;
use App\Models\RestrictionMember;
use App\Models\Restriction;
use App\Models\ProjectUser;
use App\Models\RestrictionFront;
use App\Models\RestrictionPhase;
use Illuminate\Http\Request;

class RestrictionController extends Controller
{
    //
    public function update_member(Request $request) {
        $restrictionid = Restriction::where('codProyecto', $request['projectId'])->get('codAnaRes');
        
        $checkuser = RestrictionMember::where('codProyecto', $request['projectId'])->delete();
        foreach($request['users'] as $user) {
            $projectuserid = ProjectUser::where('codProyecto', $request['projectId'])->where('desCorreo', $user)->get('codProyIntegrante');
            $restrictionmember = RestrictionMember::create([
                'codProyecto' => $request['projectId'],
                'codAnaRes' => $restrictionid[0]['codAnaRes'],
                'codEstado' => 1,
                'dayFechaCreacion' => $request['date'],
                'desUsuarioCreacion' => '',
                'codProyIntegrante' => $projectuserid[0]['codProyIntegrante']
            ]);
        }
        
        /* $res = RestrictionMember::where('codProyecto', $request['projectId'])->update([
            'desUsuarioCreacion' => $request['users']
        ]); */
        return $restrictionid;
    }
    public function add_front(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['id'])->get('codAnaRes');
        $resFront = RestrictionFront::create([
            'desAnaResFrente' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codProyecto' => $request['id'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        return $request;
    }

    public function add_phase(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['projectid'])->get('codAnaRes');
        $resFront = RestrictionPhase::create([
            'desAnaResFase' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codAnaResFrente' => $request['frontid'],
            'codProyecto' => $request['projectid'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        return $request;
    }

    public function get_front(Request $request) {
        $frontdata = RestrictionFront::where('codProyecto', $request['id'])->get();
        $restriction = Restriction::where('codProyecto', $request['id'])->get();
        $anaresdata = [];
        foreach($frontdata as $eachdata) {
            $tempdata = [
                'id' => $eachdata['codAnaResFrente'],
                'name' => $eachdata['desAnaResFrente'],
                'isOpen' => false,
                'info' => [],
            ];
            $phasedata = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            foreach($phasedata as $sevdata) {
                $temp = [
                    'id' => $sevdata['codAnaResFase'],
                    'name' => $sevdata['desAnaResFase'],
                    'notDelayed' => $restriction[0]['indNoRetrasados'],
                    'delayed' => $restriction[0]['indRetrasados'],
                    'tableData' => [],
                    'hideCols' => [],
                ];
                $temptable = [
                    'exercise' => "Lorem ipsum dolor sit amet, consectetu...",
                    'restriction' => "Lorem ipsum dolor sit amet, consectetu...",
                    'date_required' => "23/07/2020",
                    'responsible' => "Lizeth Marzano",
                    'responsible_area' => "Arquitectura",
                    'applicant' => "Lizeth Marzano",
                ];
                array_push($temp['tableData'], $temptable);
                array_push($tempdata['info'], $temp);
            }
            array_push($anaresdata, $tempdata);
        }
        return $anaresdata;
    }
}
