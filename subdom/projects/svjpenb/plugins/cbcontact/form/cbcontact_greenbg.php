<?php
                        //Name   
                        $cbcontactform .= "\r\n\t".'<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Nb').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Nb').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Nb')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Nb')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Nb');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Nb').']">';
                        $cbcontactform .= "\r\n\t".'<div class="cbclearleft"></div>';

                        //Email
                        $cbcontactform .= "\r\n\t".'<div class="cbleft">(*)';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= "\r\n\t".'<input class="cbright" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Em').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Em').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Em')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Em')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Em');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Em').']">';
                        $cbcontactform .= "\r\n\t".'<div class="cbclearleft"></div>';

                        //Subject
                        $cbcontactform .= "\r\n\t".'<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Sub').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Sub').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Sub')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Sub')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Sub');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Sub').']">';
                        $cbcontactform .= "\r\n\t".'<div class="cbclearleft"></div>';

                        //Message   
                        $cbcontactform .= "\r\n\t".'<div class="cbleft">(*)';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= "\r\n\t".'<textarea class="cbright" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Ms').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Ms').'&quot;) {this.value = &quot;&quot;;}" name="contact['.i18n_r('cbcontact/Ms').']">';
                        if ($mi_arrayq[i18n_r('cbcontact/Ms')] != "") {
                            $cbcontactform .= "\r\n".$mi_arrayq[i18n_r('cbcontact/Ms')];
                        } else {
                            $cbcontactform .= "\r\n".i18n_r('cbcontact/Ms');
                        }
                        $cbcontactform .= '</textarea>'; 

                        //system captcha
                        if ($captcha === true or $captcha == 'true'){
								$cbcontactform .= "\r\n\t".'<div class="cbcaptcha">';
                                 $cbcontactform .= "\r\n\t\t".'<div class="cbcaptcha_label">'.i18n_r('cbcontact/veri').' <span class="cbreload_label"> '.i18n_r('cbcontact/rl').'</span></div>';
                             	 $cbcontactform .= "\r\n\t\t".'<div class="cbclear"></div>';
                                 $cbcontactform .= "\r\n\t\t".'<div>';
                                                 $cbcontactform .= "\r\n\t\t\t".'<img id="captcha" class="cbcaptchaimg" src="'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.GSPLUGINPATH.'cbcontact/" alt=" " />';
                                                 $cbcontactform .= "\r\n\t\t\t".'<input class="cbcaptcha_input" type="text" value="" name="contact[pot]" />';
                                                 $cbcontactform .= "\r\n\t\t\t".'<input class="cbreload" type="button" value="'.i18n_r('cbcontact/rl_but').'" onClick="javascript:rec_cpt(&quot;captcha&quot;,&quot;'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.$mGSPLUGINPATH.'&quot;)" />';
                                 $cbcontactform .= "\r\n\t\t".'</div>';
                                 $cbcontactform .= "\r\n\t\t".'<div class="cbclearleft"></div>';
                                 $cbcontactform .= "\r\n\t\t".'<div class="cbcaptcha_write"> (*)'.i18n_r('cbcontact/Cpt').'</div>';
                            $cbcontactform .= "\r\n\t".'</div>';
                            $cbcontactform .= "\r\n\t".'<div class="cbclear"></div>';
                        } else {
                            $cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[pot]" value="">';
                        }

                        //submit button  
                        $cbcontactform .= "\r\n\t".'<div class="cbpadleft">';                              
                                 $cbcontactform .= "\r\n\t\t".'<input class="cbsend" type="submit" value="'.i18n_r('cbcontact/Ev').'" id="contact-submit" name="contact-submit" />';
    			         $cbcontactform .= "\r\n\t\t".'<span class="cbreload_label">(*) '.i18n_r('cbcontact/Rf').'</span>';
                        $cbcontactform .= "\r\n\t".'</div>';

?>
