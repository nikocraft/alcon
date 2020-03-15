<template>
    <div class="col-md-12">
        <div class="box box-primary" style="min-height: 525px;">
            <div class="box-header">
                <h3 class="box-title">Auth Page Customization</h3>
                <div class="header-tools">
                    <button @click="save" name="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div v-if="settings" class="box-body noselect">
                <!-- <div class="form-group">
                    <label>Background Color</label>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
                </div> -->

                <!-- <div class="form-group">
                    <label>Background Image</label>
                    <div class="input-group">
                        <input type="text" v-model="settings.backgroundImage" class="form-control"/>
                        <span @click="openMediaModal('backgroundImage')" class="input-group-addon" style="cursor: pointer; border-left: 0;">Media</span>
                    </div>
                </div> -->

                <div class="form-group">
                    <label>Logo</label>
                    <select name="logoType" class="form-control" v-model="settings.logoType">
                        <option value="image">Image</option>
                        <option value="text">Text</option>
                    </select>
                </div>

                <div v-if="settings.logoType=='image'" class="form-group">
                    <label>Logo Image</label>
                    <div class="input-group">
                        <input name="logoImage" type="text" v-model="settings.logoImage" class="form-control"/>
                        <span @click="openMediaModal('logoImage')" class="input-group-addon" style="cursor: pointer; border-left: 0;">Media</span>
                    </div>
                </div>

                <div v-if="settings.logoType=='text'" class="form-group">
                    <label>Logo Text</label>
                    <input name="logoText" type="text" class="form-control" placeholder="Enter logo text" v-model="settings.logoText">
                </div>

                <!-- <div class="form-group">
                    <label>Custom Css</label>
                    <textarea name="customCss" class="form-control" rows="12" v-model="settings.customCss"></textarea>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                settings: null
            }
        },
        methods: {
            save() {
                let formData = {
                    settings: this.settings
                }

                return axios.post(route('api.settings.auth.store'), formData)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        //
                    })
            },
            openMediaModal(insertInto) {
                var mode = 'insertImage'
                var params = new Object

                params.cb = (image) => {
                    this.settings[insertInto] = '/' + image.path + image.filename + '.' + image.extension
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
        },
        mounted() {
            return axios.get(route('api.settings.auth.index'))
                .then(({data}) => {
                    this.settings = data.data
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
</script>
