<?php
$config = array(
                 'main/claim' => array(
                                    array(
                                            'field' => 'name[]',
                                            'label' => 'Name',
                                            'rules' => 'trim|required'
                                         ),
                                    array(
                                            'field' => 'nric[]',
                                            'label' => 'NRIC',
                                            'rules' => 'trim|required'
                                         ),
                                    array(
                                            'field' => 'hours[]',
                                            'label' => 'Hours',
                                            'rules' => 'trim|required|numeric'
                                         )
                                    ),
                 'admin/edit_claim' => array(
                                    array(
                                            'field' => 'name[]',
                                            'label' => 'Name',
                                            'rules' => 'trim|required'
                                         ),
                                    array(
                                            'field' => 'nric[]',
                                            'label' => 'NRIC',
                                            'rules' => 'trim|required'
                                         ),
                                    array(
                                            'field' => 'hours[]',
                                            'label' => 'Hours',
                                            'rules' => 'trim|required|numeric'
                                         )
                                    )
               );