<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">RUC</label>
                            <el-input :disabled="form.is_update" v-model="form.number" :maxlength="11" dusk="number">
                                <el-button :disabled="form.is_update" type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchSunat">
                                    SUNAT
                                </el-button>
                            </el-input>
                        </div>

                        <!--<div class="form-group" :class="{'has-danger': errors.number}">-->
                            <!--<label class="control-label">RUC</label>-->
                            <!--<el-input v-model="form.number" :maxlength="11" dusk="number"></el-input>-->
                            <!--<small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>-->
                        <!--</div>-->
                    </div>
                    <div class="col-md-6">

                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre de la Empresa</label>
                            <el-input :disabled="form.is_update" v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div v-if="form.is_update" class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
                            <label class="control-label">Nombre de Subdominio</label>
                            <el-input :disabled="form.is_update" v-model="form.hostname" dusk="name"></el-input>
                        </div>
                        <div v-else class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
                            <label class="control-label">Nombre de Subdominio</label>
                            <el-input v-model="form.subdomain" dusk="subdomain">
                                <template slot="append">{{ url_base }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.subdomain" v-text="errors.subdomain[0]"></small>
                            <small class="form-control-feedback" v-if="errors.uuid" v-text="errors.uuid[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo de Acceso</label>
                            <el-input :disabled="form.is_update" v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" v-if="!form.is_update">
                        <div class="form-group" :class="{'has-danger': (errors.password)}">
                            <label class="control-label">Contraseña</label>
                            <el-input type="password" :disabled="form.is_update" v-model="form.password" dusk="password"></el-input>
                            <small class="form-control-feedback" v-if="errors.password" v-text="errors.password[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.plan_id}">
                            <label class="control-label">Plan</label>
                            <el-select v-model="form.plan_id" dusk="plan_id">
                                <el-option v-for="option in plans" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.plan_id" v-text="errors.plan_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="!form.is_update">
                        <div  class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label">Perfil</label>
                            <el-select :disabled="form.is_update" v-model="form.type">
                                <el-option v-for="option in types" :key="option.type" :value="option.type" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6 center-el-checkbox mt-4">
                        <div class="form-group" :class="{'has-danger': errors.locked_emission}">
                            <el-checkbox :disabled="form.is_update" v-model="form.locked_emission">Limitar emisión de documentos</el-checkbox><br>
                            <small class="form-control-feedback" v-if="errors.locked_emission" v-text="errors.locked_emission[0]"></small>
                        </div>
                    </div>


                </div>
                <div class="row">
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="control-label">Módulos</label>
                            <div class="row">
                                <div class="col-4" v-for="(module,ind) in form.modules" :key="ind">
                                    <el-checkbox v-model="module.checked">{{ module.description }}</el-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12 mt-2">
                        <h4 class="border-bottom">Entorno del sistema</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.soap_send_id}">
                            <label class="control-label">SOAP Envio</label>
                            <el-select v-model="form.soap_send_id">
                                <el-option v-for="(option, index) in soap_sends" :key="index" :value="option.value" :label="option.text"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.soap_send_id" v-text="errors.soap_send_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.soap_type_id}">
                            <label class="control-label">SOAP Tipo</label>
                            <el-select v-model="form.soap_type_id">
                                <el-option v-for="option in soap_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>

                            <el-checkbox
                                   v-if="form.soap_send_id == '02' && form.soap_type_id == '01'"
                                   v-model="toggle"
                                   label="Ingresar Usuario">
                            </el-checkbox>
                            <small class="form-control-feedback" v-if="errors.soap_type_id" v-text="errors.soap_type_id[0]"></small>
                        </div>
                    </div>
                </div>
                <template v-if="form.soap_type_id == '02' || toggle == true ">
                    <div class="row" >
                        <div class="col-md-12 mt-2">
                            <h4 class="border-bottom">Usuario Secundario Sunat</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.soap_username}">
                                <label class="control-label">SOAP Usuario <span class="text-danger">*</span></label>
                                <el-input v-model="form.soap_username"></el-input>
                                <div class="sub-title text-muted"><small>RUC + Usuario. Ejemplo: 01234567890ELUSUARIO</small></div>
                                <small class="form-control-feedback" v-if="errors.soap_username" v-text="errors.soap_username[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.soap_password}">
                                <label class="control-label">SOAP Password <span class="text-danger">*</span></label>
                                <el-input v-model="form.soap_password"></el-input>
                                <small class="form-control-feedback" v-if="errors.soap_password" v-text="errors.soap_password[0]"></small>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="row" v-if="form.soap_send_id == '02'">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.soap_url}">
                            <label class="control-label">SOAP Url</label>
                            <el-input v-model="form.soap_url"></el-input>
                            <small class="form-control-feedback" v-if="errors.soap_url" v-text="errors.soap_url[0]"></small>
                        </div>
                    </div>
                </div> <br>
                <div  class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.password_certificate}">
                            <label class="control-label">Contraseña certificado</label>
                            <el-input v-model="form.password_certificate"></el-input>
                            <small class="form-control-feedback" v-if="errors.password_certificate" v-text="errors.password_certificate[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.certificate}">
                            <label class="control-label">Certificado pfx</label>
                            <el-upload
                                       ref="upload"
                                       :headers="headers"
                                       :data="{'type': 'certificate'}"
                                       :action="`/${resource}/upload`"
                                       :show-file-list="false"
                                       :multiple="false"
                                       :on-error="errorUpload"
                                       :on-success="successUpload">
                                <el-button slot="trigger" type="primary">Selecciona un archivo</el-button>
                            </el-upload>
                            <small class="form-control-feedback" v-if="errors.certificate" v-text="errors.certificate[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-show="form.is_update == false && certificate_admin">
                        <div class="form-group">
                            <label class="control-label">Archivo cargado (Administrador) </label>
                            <el-input :disabled="true" v-model="certificate_admin"></el-input>

                        </div>
                    </div>
                    <div class="col-md-6" v-show="form.is_update == true">
                        <div class="form-group">
                            <label class="control-label">Archivo cargado (Cliente) {{form.certificate ? '(1)' : '(0)'}}  </label>
                            <el-input :disabled="true" v-model="form.certificate"></el-input>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 center-el-checkbox mt-4">
                        <div class="form-group">
                            <el-checkbox v-model="form.config_system_env">¿ Permitir a la empresa cambiar la configuración de producción ?</el-checkbox><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" dusk="submit">
                    <template v-if="loading_submit">
                        {{button_text}}
                    </template>
                    <template v-else>
                        Guardar
                    </template>
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>


<script>

    import {serviceNumber} from '../../../mixins/functions'

    export default {
        mixins: [serviceNumber],
        props: ['showDialog', 'recordId'],
        data() {
            return {
                headers: headers_token,
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                button_text:null,
                resource: 'clients',
                error: {},
                errors: {},
                form: {},
                url_base: null,
                plans:[],
                modules: [],
                types:[],
                soap_sends: [ { value: '01', text: 'Sunat' }, { value: '02', text: 'Ose' }],
                soap_types: [{id: "01", description: "Demo"}, {id: "02", description: "Producción"}],
                toggle: false,
                certificate_admin: '',
                soap_username:  null,
                soap_password:  null

            }
        },
        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.url_base = response.data.url_base
                    this.plans = response.data.plans
                    this.modules = response.data.modules
                    this.types = response.data.types
                    this.certificate_admin = response.data.certificate_admin
                    this.soap_username = response.data.soap_username
                    this.soap_password = response.data.soap_password
                })

            await this.initForm()
        
            this.form.soap_username = this.soap_username
            this.form.soap_password = this.soap_password


        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    email: null,
                    identity_document_type_id: '6',
                    number: '',
                    password:null,
                    plan_id:null,
                    locked_emission:false,
                    type:null,
                    is_update:false,
                    modules: [],
                    config_system_env: true,
                    soap_send_id: '01',
                    soap_type_id: '01',
                    soap_username: null,
                    soap_password: null,
                    soap_url: null,
                    password_certificate: null,
                    certificate: null,
                    temp_path: null,
                }

                this.modules.forEach(module => {
                    this.form.modules.push({
                        id: module.id,
                        description: module.description,
                        checked: true
                    })
                })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data
                                this.form.is_update = true
                            })
                }
            },
            hasModules(){

                let modules_checked = 0
                this.form.modules.forEach(module =>{
                    if(module.checked){
                        modules_checked++
                    }
                })

                return (modules_checked > 0) ? true:false

            },
            async submit() {
                // console.log(this.form)
                let has_modules = await this.hasModules()
                if(!has_modules)
                    return this.$message.error('Debe seleccionar al menos un módulo')


                if(!this.form.is_update)
                {
                    if(this.form.certificate && !this.form.password_certificate)
                    {
                     return this.$message.error('Si carga un certificado, es necesario ingresar el password del certificado')
                    }
                }else
                {
                    if(this.form.temp_path && !this.form.password_certificate){
                         return this.$message.error('Si carga un certificado, es necesario ingresar el password del certificado')
                    }
                }



                this.button_text = (this.form.is_update) ? 'Actualizando cliente...':'Creando base de datos...'
                this.loading_submit = true
                await this.$http.post(`${this.resource}${(this.form.is_update ? '/update' : '')}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        }else if(error.response.status === 500){
                            this.$message.error(error.response.data.message);
                        }
                         else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            searchSunat() {
                this.searchServiceNumber()
            },
            errorUpload(r)
            {
                console.log(r)
            },
            successUpload(response)
            {
                if (response.success) {
                    this.form.certificate = response.data.filename
                   // this.form.image_url = response.data.temp_image
                    this.form.temp_path = response.data.temp_path
                } else {
                    this.$message.error(response.message)
                }
            }
        }
    }
</script>
