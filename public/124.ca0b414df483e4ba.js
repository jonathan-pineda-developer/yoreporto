"use strict";(self.webpackChunkSyndash_angular=self.webpackChunkSyndash_angular||[]).push([[124],{9124:(I,p,n)=>{n.r(p),n.d(p,{UserProfileModule:()=>q});var f=n(9808),d=n(1810),i=n(3075),g=n(5226),c=n.n(g),v=n(1005),e=n(4893),h=n(1305),b=n(7592),Z=n(3709);function _(a,l){if(1&a&&(e.TgZ(0,"div",53),e._UZ(1,"img",54),e.qZA()),2&a){const r=e.oxw();e.xp6(1),e.Q6J("src",r.imgUrl1,e.LSH)}}function U(a,l){if(1&a){const r=e.EpF();e.TgZ(0,"div",53)(1,"img",55),e.NdJ("click",function(){e.CHM(r);const t=e.oxw();return t.abrirModal(t.usuario)}),e.qZA()()}if(2&a){const r=e.oxw();e.xp6(1),e.Q6J("src",r.imgUrl1,e.LSH)}}function w(a,l){1&a&&(e.TgZ(0,"li",21)(1,"a",56)(2,"span",23),e._uU(3,"Cambiar contrase\xf1a"),e.qZA(),e._UZ(4,"i",57),e.qZA()())}function C(a,l){if(1&a&&(e.TgZ(0,"p"),e._uU(1),e.qZA()),2&a){const r=e.oxw();e.xp6(1),e.Oqu(r.errorMensajeContrasena)}}function P(a,l){if(1&a&&(e.TgZ(0,"p"),e._uU(1),e.qZA()),2&a){const r=e.oxw();e.xp6(1),e.Oqu(r.errorMensajeContrasenaN)}}function x(a,l){1&a&&(e.TgZ(0,"p"),e._uU(1,"Las contrase\xf1as no son iguales"),e.qZA())}const T=[{path:"",component:(()=>{class a{constructor(r,o,t,s,u,m,F,N){this.fb=r,this.file=o,this.modalImagenService=t,this.router=s,this.route=u,this.authService=m,this.changeDetectorRef=F,this.ngZone=N,this.imgUrl="",this.imgUrl1="",this.regex="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[()$@$!%*?&])([*[a-z].*[a-z].*[a-z]d$@$!%*?&]|[^ ]){8,20}$",this.resetPasswordForm=this.fb.group({contrasenaActual:["",[i.kI.required]],contrasenaNueva:["",[i.kI.required,i.kI.minLength(8),i.kI.pattern(this.regex)]],contrasenaConfirmar:["",[i.kI.required,i.kI.minLength(8)]]},{validators:this.passwordsIguales("contrasenaNueva","contrasenaConfirmar")}),this.usuario=m.usuario,console.log(this.usuario),this.imgUrl=m.usuario.imagenUrl,this.imgUrl1=this.photoURL(),console.log(this.imgUrl)}contrasenasNoValidas(){return this.resetPasswordForm.get("contrasenaNueva").value!==this.resetPasswordForm.get("contrasenaConfirmar").value}cambiarContra(){if(this.resetPasswordForm.invalid)return void this.resetPasswordForm.markAllAsTouched();const{contrasenaActual:r,contrasenaNueva:o,contrasenaConfirmar:t}=this.resetPasswordForm.value;this.authService.cambiarContraPerfil(r,o,t).subscribe(s=>{c().fire("Exito","Se ha cambiado la contrase\xf1a","success"),this.resetPasswordForm.reset()},s=>{c().fire("Error",s.error.message,"error")})}get errorMensajeContrasena(){var r;const o=null===(r=this.resetPasswordForm.get("contrasenaActual"))||void 0===r?void 0:r.errors;return(null==o?void 0:o.required)?"La contrase\xf1a es obligatoria":""}get errorMensajeContrasenaN(){var r;const o=null===(r=this.resetPasswordForm.get("contrasenaNueva"))||void 0===r?void 0:r.errors;return(null==o?void 0:o.required)?"La contrase\xf1a es obligatoria":(null==o?void 0:o.minlength)?"La contrase\xf1a debe tener al menos 8 caracteres":(null==o?void 0:o.pattern)?"La contrase\xf1a debe tener al menos una letra may\xfascula, una min\xfascula, un n\xfamero y un caracter especial":""}campoValido(r){var o,t;return!!((null===(o=this.resetPasswordForm.get(r))||void 0===o?void 0:o.invalid)&&this.resetPasswordForm&&(null===(t=this.resetPasswordForm.get(r))||void 0===t?void 0:t.touched))}passwordsIguales(r,o){return t=>{const u=t.controls[o];u.errors&&!u.errors.passwordsIguales||u.setErrors(t.controls[r].value===u.value?null:{noEsIgual:!0})}}onLogin(){this.router.navigate(["/auth/login"])}ngOnDestroy(){this.imgSub.unsubscribe()}photoURL(){return this.usuario?this.imgUrl.slice(1):""}ngOnInit(){$(document).ready(function(){$("#show_hide_password a").on("click",function(r){r.preventDefault(),"text"==$("#show_hide_password input").attr("type")?($("#show_hide_password input").attr("type","password"),$("#show_hide_password i").addClass("bx-hide"),$("#show_hide_password i").removeClass("bx-show")):"password"==$("#show_hide_password input").attr("type")&&($("#show_hide_password input").attr("type","text"),$("#show_hide_password i").removeClass("bx-hide"),$("#show_hide_password i").addClass("bx-show"))})}),this.perfilForm=this.fb.group({nombre:[this.usuario.nombre,i.kI.required],apellidos:[this.usuario.apellidos,i.kI.required],email:[this.usuario.email,[i.kI.required,i.kI.email]]}),this.imgSub=this.modalImagenService.nuevaImagen.pipe((0,v.g)(1500)).subscribe(r=>{this.ngZone.run(()=>{this.refrescarPagina()})})}actualizarPerfil(){this.authService.actualizarPerfil(this.perfilForm.value).subscribe(r=>{console.log(r),c().fire("Guardado","Actualizado con \xe9xito","success")},r=>{console.warn(r)})}abrirModal(r){console.log(r),this.modalImagenService.abrirModal("usuarios",r.id,r.imagen),console.log(r.imagen)}refrescarPagina(){window.location.reload()}}return a.\u0275fac=function(r){return new(r||a)(e.Y36(i.qu),e.Y36(h.J),e.Y36(b.e),e.Y36(d.F0),e.Y36(d.gz),e.Y36(Z.e),e.Y36(e.sBO),e.Y36(e.R0b))},a.\u0275cmp=e.Xpm({type:a,selectors:[["app-user-profile"]],decls:90,vars:14,consts:[[1,"page-breadcrumb","d-none","d-sm-flex","align-items-center","mb-3"],[1,"breadcrumb-title","pe-3"],[1,"ps-3"],["aria-label","breadcrumb"],[1,"breadcrumb","mb-0","p-0"],[1,"breadcrumb-item"],["href","#",3,"click"],[1,"bx","bx-home-alt"],["aria-current","page",1,"breadcrumb-item","active"],[1,"user-profile-page"],[1,"card","radius-15"],[1,"card-body"],[1,"row"],[1,"col-12","col-lg-7","border-right"],[1,"d-md-flex","align-items-center"],["class","mb-md-0 mb-3",4,"ngIf"],[1,"ms-md-4","flex-grow-1"],[1,"d-flex","align-items-center","mb-1"],[1,"mb-0"],[1,"mb-0","text-muted"],[1,"nav","nav-pills","mt-3"],[1,"nav-item"],["data-bs-toggle","tab","href","#editar",1,"nav-link","active"],[1,"p-tab-name"],[1,"bx","bx-donate-blood","font-24","d-sm-none"],["class","nav-item",4,"ngIf"],[1,"tab-content","m-2"],["id","editar",1,"tab-pane","fade","show","active"],[1,"card","shadow-none","border","mb-0","radius-15"],[1,"form-body"],[1,"col-12"],[1,"row","g-3",3,"formGroup","submit"],[1,"col-6"],[1,"form-label"],["type","text","formControlName","nombre",1,"form-control",3,"readOnly"],["type","text","formControlName","apellidos",1,"form-control",3,"readOnly"],["type","text","formControlName","email",1,"form-control",3,"readOnly"],[1,"mt-4","col-12","d-flex","justify-content-center"],["type","submit",1,"btn","btn-primary","px-5",3,"disabled"],["id","cambiocontra",1,"tab-pane","fade"],[1,"row","g-3",3,"formGroup","ngSubmit"],[1,"col-sm-12"],["for","inputChoosePassword",1,"form-label"],["id","show_hide_password",1,"input-group"],["formControlName","contrasenaActual","type","password","id","inputChoosePassword","value","12345678","placeholder","Contrase\xf1a actual",1,"form-control","border-end-0"],["href","#",1,"input-group-text","bg-transparent",3,"click"],[1,"bx","bx-hide"],[1,"col","text-danger"],[4,"ngIf"],["formControlName","contrasenaNueva","type","password","id","inputChoosePassword","value","12345678","placeholder","Contrase\xf1a nueva",1,"form-control","border-end-0"],[1,"mb-3"],["formControlName","contrasenaConfirmar","type","password","id","inputChoosePassword","value","12345678","placeholder","Confirmar contrase\xf1a",1,"form-control","border-end-0"],["type","submit",1,"btn","btn-primary","px-5"],[1,"mb-md-0","mb-3"],["width","130","height","130","alt","",1,"rounded-circle","shadow",3,"src"],["width","130","height","130","alt","",1,"rounded-circle","shadow","cursor",3,"src","click"],["id","profile-tab","data-bs-toggle","tab","href","#cambiocontra",1,"nav-link"],[1,"bx","bxs-user-rectangle","font-24","d-sm-none"]],template:function(r,o){1&r&&(e.TgZ(0,"div",0)(1,"div",1),e._uU(2,"Perfil Usuario"),e.qZA(),e.TgZ(3,"div",2)(4,"nav",3)(5,"ol",4)(6,"li",5)(7,"a",6),e.NdJ("click",function(s){return s.preventDefault()}),e._UZ(8,"i",7),e.qZA()(),e.TgZ(9,"li",8),e._uU(10,"Perfil Usuario"),e.qZA()()()()(),e.TgZ(11,"div",9)(12,"div",10)(13,"div",11)(14,"div",12)(15,"div",13)(16,"div",14),e.YNc(17,_,2,1,"div",15),e.YNc(18,U,2,1,"div",15),e.TgZ(19,"div",16)(20,"div",17)(21,"h4",18),e._uU(22),e.qZA()(),e.TgZ(23,"p",19),e._uU(24),e.qZA()()()(),e.TgZ(25,"ul",20)(26,"li",21)(27,"a",22)(28,"span",23),e._uU(29,"Editar perfil"),e.qZA(),e._UZ(30,"i",24),e.qZA()(),e.YNc(31,w,5,0,"li",25),e.qZA(),e.TgZ(32,"div",26)(33,"div",27)(34,"div",28)(35,"div",11)(36,"div",29)(37,"div",30)(38,"form",31),e.NdJ("submit",function(){return o.actualizarPerfil()}),e.TgZ(39,"div",32)(40,"label",33),e._uU(41,"Nombre"),e.qZA(),e._UZ(42,"input",34),e.qZA(),e.TgZ(43,"div",32)(44,"label",33),e._uU(45,"Apellidos"),e.qZA(),e._UZ(46,"input",35),e.qZA(),e.TgZ(47,"div",30)(48,"label",33),e._uU(49,"Email"),e.qZA(),e._UZ(50,"input",36),e.qZA(),e.TgZ(51,"div",37)(52,"button",38),e._uU(53,"Actualizar"),e.qZA()()()()()()()(),e.TgZ(54,"div",39)(55,"div",28)(56,"div",11)(57,"div",29)(58,"div",30)(59,"form",40),e.NdJ("ngSubmit",function(){return o.cambiarContra()}),e.TgZ(60,"div",41)(61,"label",42),e._uU(62,"Contrase\xf1a actual"),e.qZA(),e.TgZ(63,"div",43),e._UZ(64,"input",44),e.TgZ(65,"a",45),e.NdJ("click",function(s){return s.preventDefault()}),e._UZ(66,"i",46),e.qZA()(),e.TgZ(67,"div",47),e.YNc(68,C,2,1,"p",48),e.qZA()(),e.TgZ(69,"div",41)(70,"label",42),e._uU(71,"Contrase\xf1a nueva"),e.qZA(),e.TgZ(72,"div",43),e._UZ(73,"input",49),e.TgZ(74,"a",45),e.NdJ("click",function(s){return s.preventDefault()}),e._UZ(75,"i",46),e.qZA()(),e.TgZ(76,"div",47),e.YNc(77,P,2,1,"p",48),e.qZA()(),e.TgZ(78,"div",50)(79,"label",42),e._uU(80,"Confirmar contrase\xf1a"),e.qZA(),e.TgZ(81,"div",43),e._UZ(82,"input",51),e.TgZ(83,"a",45),e.NdJ("click",function(s){return s.preventDefault()}),e._UZ(84,"i",46),e.qZA()(),e.TgZ(85,"div",47),e.YNc(86,x,2,0,"p",48),e.qZA()(),e.TgZ(87,"div",37)(88,"button",52),e._uU(89,"Cambiar contrase\xf1a"),e.qZA()()()()()()()()()()()()()),2&r&&(e.xp6(17),e.Q6J("ngIf",o.usuario.google),e.xp6(1),e.Q6J("ngIf",!o.usuario.google),e.xp6(4),e.Oqu(o.usuario.nombre),e.xp6(2),e.Oqu(o.usuario.email),e.xp6(7),e.Q6J("ngIf",!o.usuario.google),e.xp6(7),e.Q6J("formGroup",o.perfilForm),e.xp6(4),e.Q6J("readOnly",o.usuario.google),e.xp6(4),e.Q6J("readOnly",o.usuario.google),e.xp6(4),e.Q6J("readOnly",o.usuario.google),e.xp6(2),e.Q6J("disabled",o.usuario.google),e.xp6(7),e.Q6J("formGroup",o.resetPasswordForm),e.xp6(9),e.Q6J("ngIf",o.campoValido("contrasenaActual")),e.xp6(9),e.Q6J("ngIf",o.campoValido("contrasenaNueva")),e.xp6(9),e.Q6J("ngIf",o.contrasenasNoValidas()))},directives:[f.O5,i._Y,i.JL,i.sg,i.Fj,i.JJ,i.u],styles:["button[_ngcontent-%COMP%]{justify-content:center;align-items:center;display:flex}.cursor[_ngcontent-%COMP%]{cursor:pointer}"]}),a})()}];let A=(()=>{class a{}return a.\u0275fac=function(r){return new(r||a)},a.\u0275mod=e.oAB({type:a}),a.\u0275inj=e.cJS({imports:[[d.Bz.forChild(T)],d.Bz]}),a})();var y=n(5436);let q=(()=>{class a{}return a.\u0275fac=function(r){return new(r||a)},a.\u0275mod=e.oAB({type:a}),a.\u0275inj=e.cJS({imports:[[f.ez,A,y.IJ,i.UX]]}),a})()}}]);