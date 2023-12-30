
<table width="100%" cellspacing="0" cellpadding="0" border="0">
   <!-- LOGO -->
   <tbody>
      <tr>
         <td bgcolor="#caefebc7" align="center" style="background: #caefebc7;">
            <table style="max-width: 600px;" width="100%" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td style="padding: 80px 10px 80px 10px;" valign="top" align="center">
                        <a>
                        <img alt="Logo" src="{{ asset('public/images/logo.jpg') }}" width="120" style="display: block; font-family: 'Lato', Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;" border="0">
                        </a>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <!-- HERO -->
      <tr>
         <td style="padding: 0px 10px 0px 10px;background: #caefebc7;" align="center">
            <table style="max-width: 600px;" width="100%" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;" valign="top" bgcolor="#ffffff" align="center">
                        <h1 style="font-size: 42px; font-weight: 400; margin: 0;">Welcome!</h1>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <!-- COPY BLOCK -->
      <tr>
         <td style="padding: 0px 10px 0px 10px;" bgcolor="#f4f4f4" align="center">
            <table style="max-width: 600px;" width="100%" cellspacing="0" cellpadding="0" border="0">
               <!-- COPY -->
               <tbody>
                  <tr>
                     <td style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" bgcolor="#ffffff" align="left">
                        <p style="margin: 0;">Hi {{ $data['name'] }}, </p>
                        <p style="margin: 14px 0;">Thanks for Signing up with Faruhi! </p>
                        <p style="margin: 0;">We would like to update you that still your account is not activated. Please click below link to activate your account. </p>
                     </td>
                  </tr>
                  <!-- BULLETPROOF BUTTON -->
                  <tr>
                     <td bgcolor="#ffffff" align="left">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                           <tbody>
                              <tr>
                                 <td style="padding: 20px 30px 60px 30px;" bgcolor="#ffffff" align="center">
                                    <table cellspacing="0" cellpadding="0" border="0">
                                       <tbody>
                                          <tr>
                                             <td style="border-radius: 0px;" align="center">
                                              <a href="{{ $data['link'] }}" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 12px 50px; background: #F43A5D; display: inline-block;">Click here to Activate</a>
                                            </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                
                  <tr>
                     <td style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" bgcolor="#ffffff" align="left">
                        <p style="margin: 0;">Thank You,<br>Faruhi Team</p>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <!-- SUPPORT CALLOUT -->

   </tbody>
</table>
