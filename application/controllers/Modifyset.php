<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modifyset extends Application
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/
     * 	- or -
     * 		http://example.com/welcome/index
     *
     * So any other public methods not prefixed with an underscore will
     * map to /welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $id = $this->input->post('selectset');
            $this->data['pagebody'] = 'modifyset';
            $this->data['chooseset'] = $this->sets->all();
            $this->data['bgfile'] = '/assets/img/background.png';
            if($id == null) {
                $setmetadata = $this->sets->get(0); 
            } else {
                $setmetadata = $this->sets->get($id);
            }
            $this->data = array_merge($this->data, (array) $setmetadata);
            $this->data['setdata'] = array(
            $this->accessories->get($setmetadata->sofaid),
            $this->accessories->get($setmetadata->tableid),
            $this->accessories->get($setmetadata->lampid),
            $this->accessories->get($setmetadata->paintingid)
            );
            
            $this->data['sofas'] = $this->accessories->getCategoryMembers(0);
            $this->data['tables'] = $this->accessories->getCategoryMembers(1);
            $this->data['lamps'] = $this->accessories->getCategoryMembers(2);
            $this->data['paintings'] = $this->accessories->getCategoryMembers(3);
                
                $this->data['sofafile'] 
                        = $this->accessories->get($setmetadata->sofaid)
                        ->filepath;
                $this->data['tablefile'] 
                        = $this->accessories->get($setmetadata->tableid)
                        ->filepath;

                $this->data['lampfile'] 
                        = $this->accessories->get($setmetadata->lampid)
                        ->filepath;

                $this->data['paintingfile'] 
                        = $this->accessories->get($setmetadata->paintingid)
                        ->filepath;                
                
        $this->data['totalvolume'] = 0.0;
        $this->data['totalweight'] = 0.0;
        $this->data['totalcost'] = 0.0;
        foreach ($this->data['setdata'] as $accessoryitem)
        {
            $this->data['totalvolume'] += $accessoryitem->itemvolume;
            $this->data['totalweight'] += $accessoryitem->itemweight;
            $this->data['totalcost'] += $accessoryitem->itemprice;
        }
                $this->data['selectedid'] = $id;
                
        
        for ($i = 0; $i < sizeof($this->data['chooseset']); ++$i)
        {
            $this->data['chooseset'][$i]->default =
                ($this->data['chooseset'][$i]->setid == $id)
                    ? "selected"
                    : "";
        }
        
        for ($i = 0; $i < sizeof($this->data['sofas']); ++$i)
        {
            $this->data['sofas'][$i]->default =
                ($this->data['sofas'][$i]->itemid == $setmetadata->sofaid)
                    ? "selected"
                    : "";
        }
        
        for ($i = 0; $i < sizeof($this->data['tables']); ++$i)
        {
            $this->data['tables'][$i]->default =
                ($this->data['tables'][$i]->itemid == $setmetadata->tableid)
                    ? "selected"
                    : "";
        }
        
        for ($i = 0; $i < sizeof($this->data['lamps']); ++$i)
        {
            $this->data['lamps'][$i]->default =
                ($this->data['lamps'][$i]->itemid == $setmetadata->lampid)
                    ? "selected"
                    : "";
        }          
              
        for ($i = 0; $i < sizeof($this->data['paintings']); ++$i)
        {
            $this->data['paintings'][$i]->default =
                ($this->data['paintings'][$i]->itemid == $setmetadata->paintingid)
                    ? "selected"
                    : "";
        }
        
        $this->data['outputname'] = $setmetadata->setfullname;
        $this->data['outputsetid'] = $setmetadata->setid;
       
        $this->render();
    }
    
        public function modify()
    {
        $this->data['pagebody'] = 'setmodified';
        $data = $this->input->post();
        if($data){
            $set = $this->sets->create();
            $set->setid = $data['submitid'];
            $set->setname = $data['submitname2'];
            $set->setfullname = $data['submitname2'];
            $set->sofaid = ($data['submitsofa'] != '{outputsofa}' ? $data['submitsofa'] : '');
            $set->tableid = ($data['submittable'] != '{outputtable}' ? $data['submittable'] : '');
            $set->lampid = ($data['submitlamp'] != '{outputlamp}' ? $data['submitlamp'] : '');
            $set->paintingid = ($data['submitpainting']!= '{outputpainting}' ? $data['submitpainting'] : '');
          //  if($set->sofaid && $set->tableid && $set->lampid && $set->paintingid) {
              $this->sets->update($set);  
            //} else {
            //    $this->data['pagebody'] = 'create';
           // }
//            $this->sets->add($set);
        }
        $this->render();
    }
    
    public function selection()
    {
        $this->data['pagebody'] = 'modifyset';
        $data = $this->input->post();
        
        
            $this->data['chooseset'] = $this->sets->all();
            $this->data['bgfile'] = '/assets/img/background.png';
            $this->data['datasets'] = $this->accessories->all();
            
            $this->data['sofas'] = $this->accessories->getCategoryMembers(0);
            $this->data['tables'] = $this->accessories->getCategoryMembers(1);
            $this->data['lamps'] = $this->accessories->getCategoryMembers(2);
            $this->data['paintings'] = $this->accessories->getCategoryMembers(3);
        
        if($data) {
            if($data['selectsofa'] != null) {
                $this->data['outputsofa'] = $data['selectsofa'];
                $this->data['sofafile'] = $this->accessories->get($data['selectsofa'])
                    ->filepath;
                $this->data['sofavisible'] = '';
                
                for ($i = 0; $i < sizeof($this->data['sofas']); ++$i)
                {
                    $this->data['sofas'][$i]->default =
                        ($this->data['sofas'][$i]->itemid == $data['selectsofa'])
                            ? "selected"
                            : "";
                }
            } else {
                $this->data['sofavisible'] = 'hidden';
            }
            
            if($data['selecttable'] != null) {
                $this->data['outputtable'] = $data['selecttable'];
                $this->data['tablefile'] = $this->accessories->get($data['selecttable'])
                    ->filepath;
                $this->data['tablevisible'] = '';
                
                //save selection
                for ($i = 0; $i < sizeof($this->data['tables']); ++$i)
                {
                    $this->data['tables'][$i]->default =
                        ($this->data['tables'][$i]->itemid == $data['selecttable'])
                            ? "selected"
                            : "";
                }                
            } else {
                $this->data['tablevisible'] = 'hidden';
            }
            
            if($data['selectlamp'] != null) {
                $this->data['outputlamp'] = $data['selectlamp'];
                $this->data['lampfile'] = $this->accessories->get($data['selectlamp'])
                    ->filepath;
                $this->data['lampvisible'] = '';
                
                //save selection
                for ($i = 0; $i < sizeof($this->data['lamps']); ++$i)
                {
                    $this->data['lamps'][$i]->default =
                        ($this->data['lamps'][$i]->itemid == $data['selectlamp'])
                            ? "selected"
                            : "";
                }                
            } else {
                $this->data['lampvisible'] = 'hidden';
            }
            
            if($data['selectpainting'] != null) {
                $this->data['outputpainting'] = $data['selectpainting'];
                $this->data['paintingfile'] = $this->accessories->get($data['selectpainting'])
                    ->filepath;
                $this->data['paintingvisible'] = '';
                
                //save selection
                for ($i = 0; $i < sizeof($this->data['paintings']); ++$i)
                {
                    $this->data['paintings'][$i]->default =
                        ($this->data['paintings'][$i]->itemid == $data['selectpainting'])
                            ? "selected"
                            : "";
                }                
            } else {
                $this->data['paintingvisible'] = 'hidden';
            }
        
            $this->data['outputname'] = $data['submitname'];
            $this->data['outputsetid'] = $data['submitid'];    

            for ($i = 0; $i < sizeof($this->data['chooseset']); ++$i)
            {
                $this->data['chooseset'][$i]->default =
                    ($this->data['chooseset'][$i]->setid == $data['submitid'])
                        ? "selected"
                        : "";
            }
        
        }

        $this->render();
    }
}