 <!-- Modal info member -->
 <div class="modal fade" id="modal-dialog-info">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title">Info Member</h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
             </div>
             <div class="modal-body">
                 <div class="row d-flex">
                     <div class="col-xl-4">
                         <div class="text-center mb-3">
                             <img src="" alt="Foto Member" id="image-member" width="100">
                         </div>

                         <div id="qrcode" class="d-flex justify-content-center"></div>

                         <div class="d-block mt-3 text-center">
                             <a href="" class="btn btn-sm btn-outline-primary" id="btn-print-qr" target="_blank"><i class="fas fa-print me-1"></i> Print QR Member</a>
                         </div>
                     </div>

                     <div class="col-xl-8">
                         <h4 id="info-name"></h4>

                         <div class="row d-flex mb-3 justify-content-between flex-wrap">
                             <div class="col-md-4">
                                 <span class="d-block">RFID</span>
                                 <b class="fs-14px" id="info-rfid"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">No. Identitas</span>
                                 <b class="fs-14px" id="info-id"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">No. Hp</span>
                                 <b class="fs-14px" id="info-phone"></b>
                             </div>
                         </div>

                         <div class="row d-flex mb-3 justify-content-between flex-wrap">
                             <div class="col-md-4">
                                 <span class="d-block">Jenis Kelamin</span>
                                 <b class="fs-14px" id="info-gender"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">Tanggal Lahir</span>
                                 <b class="fs-14px" id="info-birth"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">Alamat</span>
                                 <b class="fs-14px" id="info-address"></b>
                             </div>
                         </div>

                         <div class="row d-flex mb-3 justify-content-between flex-wrap">
                             <div class="col-md-4">
                                 <span class="d-block">Membership</span>
                                 <b class="fs-14px" id="info-membership"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">Tanggal Register</span>
                                 <b class="fs-14px" id="info-register"></b>
                             </div>

                             <div class="col-md-4">
                                 <span class="d-block">Tanggal Expired</span>
                                 <b class="fs-14px" id="info-expired"></b>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
             </div>
         </div>
     </div>
 </div>