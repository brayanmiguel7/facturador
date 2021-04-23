<template>
    <div class="row">
        <div class="card col-md-8">
            <div class="card-header justify-content-center d-block">
                <div class="text-center mt-2">
                    <el-button @click.prevent="start()" :loading="loading_submit">Iniciar Proceso</el-button>
                </div>
                <p class="mb-0">Espacio disponible en disco: {{discUsed}}</p>
                <p class="mb-0">Espacio ocupado por archivos de facturación: {{storageSize}}</p>
            </div>
            <div class="card-body">

                <h3>Backups</h3>

                <div v-if="db.status == 'success'">
                    <h3>Base de datos <span v-if="db.error == ''" class="text-success"><i class="fas fa-check"></i></span></h3>
                    <h4>Log: {{db.content}}</h4>
                    <span v-if="db.error ==! ''" class="text-danger">{{db.error}}</span>

                </div>

                <div v-if="files.status == 'success'">
                    <h3>Archivos <span v-if="files.error == ''" class="text-success"><i class="fas fa-check"></i></span></h3>
                    <h4>Log: {{files.content}}</h4>
                    <span class="text-danger">{{files.error}}</span><br>
                </div>

            </div>
        </div>
        <div class="card col-md-4 mt-0">
            <div class="card-header">
                Enviar por FTP último backup generado
            </div>
            <div class="card-body">
                <p v-if="newLastZip !== ''">Ultimo Backup generado: {{newLastZip.name}}</p>
                <small class="text-muted">Por seguridad sus datos FTP no son guardados</small>
                <form v-if="newLastZip !== ''">
                    <div class="form-group" :class="{'has-danger': errors.host}">
                        <label class="control-label">Host/IP</label>
                        <el-input v-model="form.host"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.port}">
                        <label class="control-label">Puerto</label>
                        <el-input v-model="form.port"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.username}">
                        <label class="control-label">Usuario</label>
                        <el-input v-model="form.username"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.password}">
                        <label class="control-label">Contraseña</label>
                        <el-input v-model="form.password"></el-input>
                    </div>
                    <div v-if="newLastZip !== ''" class="form-group">
                        <el-button @click.prevent="uploadFtp()" :loading="loading_upload">Enviar</el-button>

                        <el-button @click.prevent="clickDownload()" >Descargar</el-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery'

    export default {
        props: ['storageSize','discUsed', 'lastZip'],
        data() {
            return {
                headers: null,
                resource: 'backup',
                errors: {},
                form: {},
                loading_submit: false,
                loading_upload: false,
                db: {
                    error: '',
                    content: '',
                    status: '',
                },
                files: {
                    error: '',
                    content: '',
                    status: '',
                },
                newLastZip: ''
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            clickDownload() {
                window.open(`/${this.resource}/download/${this.newLastZip.name}`, '_blank');
            },
            initForm(){

                this.form = {
                    host: null,
                    port: null,
                    username: null,
                    password: null,
                }

                this.newLastZip = this.lastZip

            },
            async start() {
                this.initContent()
                this.loading_submit = true
                this.backupDb()
            },
            initContent() {
                this.db.error = ''
                this.db.content = ''
                this.db.status = false
                this.files.error = ''
                this.files.content = ''
                this.files.status = false
            },
            backupDb() {
                this.$http.get(`/${this.resource}/db`)
                .then(response => {
                    if (response.data !== '') {
                        this.db.content = response.data
                        if (response.status === 200) {
                            this.db.status = 'success'
                        }
                        this.backupFiles()
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.db.error = error.response.data.message
                        this.db.status = 'false'
                    } else {
                        console.log(error)
                    }
                })
            },
            backupFiles() {
                this.$http.get(`/${this.resource}/files`)
                    .then(response => {
                        if (response.data !== '') {
                            this.files.content = response.data
                            if (response.status === 200) {
                                this.files.status = 'success'
                                this.mostRecent()
                            }
                            this.loading_submit = false
                        }
                    }).catch(error => {
                        if (error.response.status !== 200) {
                            this.files.error = error.response.data.message
                            this.files.status = 'false'
                        } else {
                            console.log(error)
                        }
                    })

            },
            mostRecent(){
                
                this.$http.get(`/${this.resource}/last-backup`)
                    .then(response => {
                        if (response.data !== '') {
                            // console.log(response)
                            this.newLastZip = response.data
                            this.loading_submit = false
                        }
                    }).catch(error => {
                        if (error.response.status !== 200) {
                            this.files.error = error.response.data.message
                        } else {
                            console.log(error)
                        }
                    })
            },
            uploadFtp() {
                this.loading_upload = true
                this.sendFtp()
            },
            sendFtp() {
                this.$http.post(`${this.resource}/upload`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.loading_upload = false
                            // this.close()
                            this.initForm()
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
                    .then(()=>{
                        this.loading_upload = false
                    })


            }
        }
    }
</script>
