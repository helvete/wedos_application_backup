<?php
                        //Name   
                        $cbcontactform .= '<input type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Nb').'&quot;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Nb').'&quot;) {this.value = &quot;&quot;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Nb')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Nb')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Nb');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Nb').']">'."\n";

                        //Email
                        $cbcontactform .= '<br />';
                        $cbcontactform .= '<input type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;(*)'.i18n_r('cbcontact/Em').'&quot;}" onfocus="if(this.value == &quot;(*)'.i18n_r('cbcontact/Em').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Em')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Em')];
                        } else {
                            $cbcontactform .= '(*)'.i18n_r('cbcontact/Em');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Em').']">'."\n";

                        //Subject
                        $cbcontactform .= '<input type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Sub').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Sub').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($mi_arrayq[i18n_r('cbcontact/Sub')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Sub')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Sub');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Sub').']">'."\n";

                        //Message   
                        $cbcontactform .= '<br />';
                        $cbcontactform .= '<textarea onblur="if (this.value == &quot;&quot;) {this.value = &quot;(*)'.i18n_r('cbcontact/Ms').'&quot;}" onfocus="if(this.value == &quot;(*)'.i18n_r('cbcontact/Ms').'&quot;) {this.value = &quot;&quot;}" name="contact['.i18n_r('cbcontact/Ms').']">';
                        if ($mi_arrayq[i18n_r('cbcontact/Ms')] != "") {
                            $cbcontactform .= $mi_arrayq[i18n_r('cbcontact/Ms')];
                        } else {
                            $cbcontactform .= '(*)'.i18n_r('cbcontact/Ms');
                        }
                        $cbcontactform .= '</textarea>'."\n";

                        //system captcha
                        if ($captcha === true or $captcha == 'true'){
							$cbcontactform .= '<div class="cbcaptcha">'."\n";
								$cbcontactform .= '<div class="cbcaptcha_label">'.i18n_r('cbcontact/veri').'</div>'."\n";
								$cbcontactform .= '<div>'."\n";
									$cbcontactform .= '<img id="captcha" class="cbcaptchaimg" src="'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.GSPLUGINPATH.'cbcontact/" alt=" " />'."\n";
									$cbcontactform .= '<input class="cbreload" type="button" value="Reload" onClick="javascript:rec_cpt(&quot;captcha&quot;,&quot;'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.$mGSPLUGINPATH.'&quot;)" title="'.i18n_r('cbcontact/rl').'" />'."\n";
								$cbcontactform .= '</div>'."\n";
								$cbcontactform .= '<div class="cbcaptcha_write"> (*)'.i18n_r('cbcontact/Cpt').'</div>'."\n";
                            $cbcontactform .= '</div>'."\n";
                        } else {
                            $cbcontactform .= '<input type="hidden" name="contact[pot]" value="">'."\n";
                        }

                        //submit button  
                        $cbcontactform .= '<div class="cbpadleft">'."\n";  
							$cbcontactform .= '<input class="cbcaptcha_input" type="text" value="" name="contact[pot]" />'."\n";                            
							$cbcontactform .= '<input class="cbsend" type="submit" value="'.i18n_r('cbcontact/Ev').'" id="contact-submit" name="contact-submit" />'."\n";
							$cbcontactform .= '<span class="cbcaptcha_write">(*) '.i18n_r('cbcontact/Rf').'</span>'."\n";
						$cbcontactform .= '</div>'."\n";

?>
