<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px :px.sync="settings.padding"></px>
                </div>
                <div v-else class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.padding"
                        :large.sync="settings.paddingLarge"
                        :medium.sync="settings.paddingMedium"
                        :small.sync="settings.paddingSmall"
                        :extraSmall.sync="settings.paddingExtraSmall"
                    >
                    </px-responsive>
                </div>

                <div v-if="!settings.marginResponsive" class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px :px.sync="settings.margin"></px>
                </div>
                <div v-else class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.margin"
                        :large.sync="settings.marginLarge"
                        :medium.sync="settings.marginMedium"
                        :small.sync="settings.marginSmall"
                        :extraSmall.sync="settings.marginExtraSmall"
                    >
                    </px-responsive>
                </div>

                <div class="form-group">
                    <label>Background Color</label>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Ref #</label>
                    <input type="text" class="form-control" v-model="settings.blockRef">
                </div>

                <div class="form-group">
                    <label>Custom Class</label>
                    <input type="text" class="form-control" v-model="settings.customClass">
                </div>
            </el-tab-pane>

            <el-tab-pane label="Columns" name="columns">
                <div class="form-group">
                    <label>Columns</label>
                    <div v-for="column in columns" :key="column.uniqueId">
                        <div class="form-group" style="margin-bottom: 4px; padding: 5px; border: 1px solid rgba(0,0,0, 0.15);">
                            <span style="padding-left: 12px; font-weight: bold;">{{ column.text }}</span>
                            <span @click="removeColumn(column.uniqueId)" style="cursor: pointer;" class="pull-right">x</span>
                        </div>
                    </div>
                    <div v-show="columns.length < 6" class="text-center">
                        <button type="button" class="btn btn-primary" style="padding: 2px 12px;"@click="addColumn">+</button>
                    </div>
                </div>

                <div v-show="settings.selectedColumns != 'zero'" class="form-group">
                    <div>
                        <label>Compatible Variations</label>
                    </div>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div @click="setColumnsWidths(columns.type)" v-for="(columns, index) in columnsCompatibleList" :key="index" :class="['column-selector-container', {'selected-columns': settings.selectedColumns == columns.type,'one-whole': settings.selectedColumns == 'one-whole','four-equal': settings.selectedColumns == 'four-equal','five-equal': settings.selectedColumns == 'five-equal','six-equal': settings.selectedColumns == 'six-equal'}]">
                            <div v-for="(column, colIndex) in columns.list" :key="colIndex" class="column-item" :style="{'padding': '5px', 'text-align': 'center', 'width': column.width}">
                                {{column.text}}
                            </div>
                        </div>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Css" name="css">
                <div class="form-group">
                    <prism-editor v-if="!$root.isFirefox" v-model="settings.css" :lineNumbers=true language="css"></prism-editor>
                    <textarea v-else v-model="settings.css" class="form-control" rows="3"></textarea>
                </div>
            </el-tab-pane>
        </el-tabs>
    </div>

</template>

<script>
    import SettingsMixin from '../../mixins/SettingsMixin'
    import { mapGetters, mapActions } from 'vuex'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [SettingsMixin],
        data() {
            return {
                columnsDefinitionList: [],
                columnsCompatibleTypes: [],
                columnsCompatibleList: [],
                columnList: [],
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Columns'},
            blockRef: {type: String, default: ''},
            customClass: {type: String, default: ''},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingLarge: {type: String, default: ''},
            paddingMedium: {type: String, default: ''},
            paddingSmall: {type: String, default: ''},
            paddingExtraSmall: {type: String, default: ''},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px auto'},
            marginLarge: {type: String, default: ''},
            marginMedium: {type: String, default: ''},
            marginSmall: {type: String, default: ''},
            marginExtraSmall: {type: String, default: ''},

            selectedColumns: {type: String, default: 'two-equal'},
            backgroundColor: {type: String, default: ''},
            css: {type: String, default: ''},
        },
        computed: {
            modalTitle() {
                return this.settings.blockTitle + ' Settings'
            },
            blockType() {
                return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
            },
            columns: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            columns(val, old) {
                if(val.length != old.length) {
                    switch (val.length) {
                        case 0:
                            this.settings.selectedColumns = 'zero'
                        break;
                        case 1:
                            this.settings.selectedColumns = 'one-whole'
                        break;
                        case 2:
                            this.settings.selectedColumns = 'two-equal'
                        break;
                        case 3:
                            this.settings.selectedColumns = 'three-equal'
                        break;
                        case 4:
                            this.settings.selectedColumns = 'four-equal'
                        break;
                        case 5:
                            this.settings.selectedColumns = 'five-equal'
                        break;
                        case 6:
                            this.settings.selectedColumns = 'six-equal'
                        break;
                        default:
                    }
                }
                this.renderCompatibleColumnVariations(val.length)
                this.setColumnsWidths(this.settings.selectedColumns)
            }
        },
        methods: {
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object

                params.cb = (image) => {
                    this.settings.backgroundImage = '/' + image.path + image.filename + '.' + image.extension
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            addColumn() {
                if(this.columns.length < 6) {
                    let compName = 'Column'
                    let comp = getComponentByName(compName)
                    if (!comp) {
                        console.error('Wrong component\'s name')
                        return false
                    }

                    let customSettings = processSettingsConfig(compName)

                    this.addItem({
                        type: compName,
                        title: 'Column',
                        parentId: this.uniqueId,
                        settingsConfig: customSettings,
                        settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                    })

                    this.updateColumnType()
                }
            },
            removeColumn(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            },
            updateColumnType() {
                var columnType = '1/1'

                switch (this.columns.length) {
                    case 1:
                        columnType = '1/1'
                    break

                    case 2:
                        columnType = '1/2'
                    break

                    case 3:
                        columnType = '1/3'
                    break

                    case 4:
                        columnType = '1/4'
                    break

                    default:
                }

                for (var i = 0; i < this.columns.length; i++) {
                    var column = this.columns[i]
                    column.text = columnType
                }
            },
            makeCompatibleColumns(loops, width, text, columns) {
                for (var i = 0; i < loops; i++) {
                    var column = new Object()
                    column.width = width
                    column.text = text
                    columns.list.push(column)
                }
            },
            renderCompatibleColumnVariations(length) {
                switch (length) {
                    case 0:
                        this.columnsCompatibleTypes = ['zero']
                    break;
                    case 1:
                        this.columnsCompatibleTypes = ['one-whole']
                    break;
                    case 2:
                        this.columnsCompatibleTypes = ['two-equal', 'two-thirds-one-third', 'one-third-two-thirds', 'one-fourth-three-fourth', 'three-fourth-one-fourth']
                    break;
                    case 3:
                        this.columnsCompatibleTypes = ['three-equal', 'one-half-one-fourth-one-fourth', 'one-fourth-one-fourth-one-half', 'one-fourth-one-half-one-fourth']
                    break;
                    case 4:
                        this.columnsCompatibleTypes = ['four-equal']
                    break;
                    case 5:
                        this.columnsCompatibleTypes = ['five-equal']
                    break;
                    case 6:
                        this.columnsCompatibleTypes = ['six-equal']
                    break;
                    default:
                }

                this.columnsCompatibleList = []
                for (var i = 0; i < this.columnsCompatibleTypes.length; i++) {
                    let columns = new Object()
                    columns.type = this.columnsCompatibleTypes[i]
                    switch (columns.type) {
                        case 'zero':
                            columns.list = []
                        break
                        case 'one-whole':
                            columns.list = []
                            this.makeCompatibleColumns(1, '100%', '1/1', columns)
                        break

                        case 'two-equal':
                            columns.list = []
                            this.makeCompatibleColumns(2, '50%', '1/2', columns)
                        break

                        case 'three-equal':
                            columns.list = []
                            this.makeCompatibleColumns(3, '33.33%', '1/3', columns)
                        break

                        case 'four-equal':
                            columns.list = []
                            this.makeCompatibleColumns(4, '25%', '1/4', columns)
                        break

                        case 'five-equal':
                            columns.list = []
                            this.makeCompatibleColumns(5, '20%', '1/5', columns)
                        break

                        case 'six-equal':
                            columns.list = []
                            this.makeCompatibleColumns(6, '16.66%', '1/6', columns)
                        break

                        case 'two-thirds-one-third':
                            columns.list = []
                            var column = new Object()
                            column.width = '60%'
                            column.text = '2/3'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '40%'
                            column.text = '1/3'
                            columns.list.push(column)
                        break


                        case 'one-third-two-thirds':
                            columns.list = []
                            var column = new Object()
                            column.width = '40%'
                            column.text = '1/3'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '60%'
                            column.text = '2/3'
                            columns.list.push(column)
                        break

                        case 'one-fourth-three-fourth':
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '75%'
                            column.text = '3/4'
                            columns.list.push(column)
                        break

                        case 'three-fourth-one-fourth':
                            columns.list = []
                            var column = new Object()
                            column.width = '75%'
                            column.text = '3/4'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        case 'one-half-one-fourth-one-fourth':
                            columns.list = []
                            var column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        case 'one-fourth-one-fourth-one-half':
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)
                        break

                        case 'one-fourth-one-half-one-fourth':
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        default:
                    }
                    this.columnsCompatibleList.push(columns)
                }
            },
            setColumnsWidths(columnType) {
                this.settings.selectedColumns = columnType
                for (var i = 0; i < this.columns.length; i++) {
                    var column = this.columns[i]

                    switch (this.settings.selectedColumns) {
                        case 'zero':
                            column.settings.width = '0%'
                            column.text = '0'
                        break
                        case 'one-whole':
                            column.settings.width = '100%'
                            column.text = '1/1'
                        break

                        case 'two-equal':
                            column.settings.width = '50%'
                            column.text = '1/2'
                        break

                        case 'three-equal':
                            column.settings.width = '33.33%'
                            column.text = '1/3'
                        break

                        case 'four-equal':
                            column.settings.width = '25%'
                            column.text = '1/4'
                        break

                        case 'five-equal':
                            column.settings.width = '20%'
                            column.text = '1/5'
                        break

                        case 'six-equal':
                            column.settings.width = '16.66%'
                            column.text = '1/6'
                        break

                        // variations of two
                        case 'two-thirds-one-third':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '66.5%'
                                    column.text = '2/3'
                                break

                                case 2:
                                    column.settings.width = '33.5%'
                                    column.text = '1/3'
                                break
                            }
                        break

                        case 'one-third-two-thirds':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '33.5%'
                                    column.text = '1/3'
                                break

                                case 2:
                                    column.settings.width = '66.5%'
                                    column.text = '2/3'
                                break
                            }
                        break

                        case 'one-fourth-three-fourth':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.settings.width = '75%'
                                    column.text = '3/4'
                                break
                            }
                        break

                        case 'three-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '75%'
                                    column.text = '3/4'
                                break

                                case 2:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        // variations of 3
                        case 'one-half-one-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '50%'
                                    column.text = '1/2'
                                break

                                case 2:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        case 'one-fourth-one-fourth-one-half':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.settings.width = '50%'
                                    column.text = '1/2'
                                break
                            }
                        break

                        case 'one-fourth-one-half-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.settings.width = '50%'
                                    column.text = '1/2'
                                break

                                case 3:
                                    column.settings.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        default:
                    }
                }
                this.initingBlock = false
            }
        },
        mounted() {
            this.setColumnsWidths(this.settings.selectedColumns)
            this.renderCompatibleColumnVariations(this.columns.length)
        }
    }
</script>

<style scoped lang="scss">
    .column-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        border: 1px solid transparent;
        width: 100%;
        // -webkit-flex-flow: row wrap;
        // justify-content: space-around;
    }

    .column-selector-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        padding: 2px 2px;
        border: 1px solid transparent;
        cursor: pointer;
        width: 33.3%;
    }

    .one-whole, .four-equal, .five-equal, .six-equal {
        width: 100%;
    }

    .selected-columns {
        border: 1px solid rgba(33, 144, 254, 0.4)
    }

    .column-item {
        background: rgba(255,255,255, 0.01);
        width: 50%;
        border: 1px solid  rgba(0,0,0, 0.15);
        margin: 0px;
        padding: 4px;
        font-size: 10px;
    }

    .column-item:first-child {
        margin-left: 0px !important;                       /*  on all but the first column  */
    }

    .column-item:last-child {
        margin-right: 0px !important;                      /*  on all but the last column  */
    }

    // .column-item:not(:first-child) {
    //     border-left: 0;
    // }
</style>
