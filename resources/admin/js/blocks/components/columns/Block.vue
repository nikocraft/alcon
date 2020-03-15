<template>
    <draggable class="column-container" :style="columnsStyles"
        v-model="columns"
        style="min-height: 120px;"
        :group="'column'+uniqueId"
        handle=".lo-icon-move"
        chosenClass="dragging1">
            <template v-for="column in columns">
                <component-wrapper
                    style="height: auto; display: flex; flex-direction: column;"
                    :style="{ 'width': column.settings.width, 'margin': '0px ' + settings.columnSpacing }"
                    class="column-item"
                    :type="column.type"
                    :uniqueId="column.uniqueId"
                    :settings="column.settings"
                    :show-headers="showBlockHeaders"
                    :show-labels="showBlockLabels"
                    :container-preview="containerPreview"
                    v-on:pick-block-modal="pickBlockModal"
                    v-on:remove="removeColumn(column.uniqueId)"
                    :storePath="storePath"
                    :key="column.uniqueId">
                </component-wrapper>
            </template>
    </draggable>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import ComponentWrapper from '../../ComponentWrapper'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        components: {
            ComponentWrapper,
            draggable
        },
        data() {
            return {
                showBlockHeaders: this.showHeaders,
                showBlockLabels: this.showLabels,
                columnsDefinitionList: [],
                columnsCompatibleTypes: [],
                columnsCompatibleList: [],
                columnList: [],
                initingBlock: true,
                cssSettingsOpen: false,
                columnsSettingsOpen: true,
                showPreview: true,
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            columnsStyles() {
                // let styles = ""
                // if(this.containerPreview) {
                //     if(this.settings.backgroundImage) {
                //         // styles = styles + 'background: linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+'), url('+this.settings.backgroundImage+');'
                //         styles = styles + 'background-image: url('+this.settings.backgroundImage+');'
                //         styles = styles + 'background-attachment: '+this.settings.backgroundStyle+';'
                //         styles = styles + 'background-position: '+this.settings.backgroundPosition+';'
                //         styles = styles + 'background-repeat: '+this.settings.backgroundRepeat+';'
                //         styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                //         switch (this.settings.backgroundSize) {
                //             case 'manual':
                //                 styles = styles + 'background-size: '+this.settings.backgroundSizeManual+';'
                //             break;
                //             default:
                //                 styles = styles + 'background-size: '+this.settings.backgroundSize+';'
                //         }
                //     }
                //     else {
                //         // styles = styles + 'background: linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+');'
                //         styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                //     }
                //     styles = styles + 'height: '+this.settings.height+';'
                //     styles = styles + 'padding: '+this.settings.padding+';'
                //     styles = styles + 'margin: '+this.settings.margin+';'
                // }
                // return styles

                //
                let styleObj = {}

                if(this.containerPreview) {
                    styleObj = {
                        height: this.settings.height,
                        padding: this.settings.padding,
                        margin: this.settings.margin
                    }

                    let tempObj = {}
                    if(this.settings.backgroundImage) {
                        tempObj = {
                            backgroundImage: `url(${this.settings.backgroundImage})`,
                            backgroundAttachment: this.settings.backgroundStyle,
                            backgroundPosition: this.settings.backgroundPosition,
                            backgroundRepeat: this.settings.backgroundRepeat,
                            boxShadow: `inset 0 0 0 2000px ${this.settings.backgroundColor}`,
                            backgroundSize: this.settings.backgroundSize
                        }
                    } else {
                        tempObj = {
                            backgroundColor: this.settings.backgroundColor
                        }
                    }

                    styleObj = {
                        ...styleObj,
                        ...tempObj
                    }
                }
                return styleObj
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
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
            showHeaders(val, old) {
                this.showBlockHeaders = val
            },
            showLabels(val, old) {
                this.showBlockLabels = val
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
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
            responsivePadding() {
                this.settings.paddingResponsive =! this.settings.paddingResponsive

                if(this.settings.paddingResponsive) {
                    this.settings.paddingTablet = this.settings.padding
                    this.settings.paddingMobile = this.settings.padding
                }
            },
            responsiveMargin() {
                this.settings.marginResponsive =! this.settings.marginResponsive

                if(this.settings.marginResponsive) {
                    this.settings.marginTablet = this.settings.margin
                    this.settings.marginMobile = this.settings.margin
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

                // console.log('render compatible variations')

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
                            column.width = '0%'
                            column.text = '0'
                        break
                        case 'one-whole':
                            column.width = '100%'
                            column.text = '1/1'
                        break

                        case 'two-equal':
                            column.width = '50%'
                            column.text = '1/2'
                        break

                        case 'three-equal':
                            column.width = '33.33%'
                            column.text = '1/3'
                        break

                        case 'four-equal':
                            column.width = '25%'
                            column.text = '1/4'
                        break

                        case 'five-equal':
                            column.width = '20%'
                            column.text = '1/5'
                        break

                        case 'six-equal':
                            column.width = '16.66%'
                            column.text = '1/6'
                        break

                        // variations of two
                        case 'two-thirds-one-third':
                            switch (i+1) {
                                case 1:
                                    column.width = '66.5%'
                                    column.text = '2/3'
                                break

                                case 2:
                                    column.width = '33.5%'
                                    column.text = '1/3'
                                break
                            }
                        break

                        case 'one-third-two-thirds':
                            switch (i+1) {
                                case 1:
                                    column.width = '33.5%'
                                    column.text = '1/3'
                                break

                                case 2:
                                    column.width = '66.5%'
                                    column.text = '2/3'
                                break
                            }
                        break

                        case 'one-fourth-three-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.width = '75%'
                                    column.text = '3/4'
                                break
                            }
                        break

                        case 'three-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '75%'
                                    column.text = '3/4'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        // variations of 3
                        case 'one-half-one-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '50%'
                                    column.text = '1/2'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        case 'one-fourth-one-fourth-one-half':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.width = '50%'
                                    column.text = '1/2'
                                break
                            }
                        break

                        case 'one-fourth-one-half-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.width = '50%'
                                    column.text = '1/2'
                                break

                                case 3:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        default:
                    }
                }
                this.initingBlock = false
            },
            pickBlockModal(columnId) {
                let params = {
                    cb: (data = {}) => {
                        let {type, identifier} = data
                        switch (type) {
                            case 'block':
                                this.$bus.$emit('column-add-block', columnId, identifier)
                                break;
                            case 'template':
                                //
                                break;
                        }
                    }
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            addColumn() {
                if(this.columns.length < 6) {
                    let compName = 'Column'
                    let comp = getComponentByName(compName)
                    if(!comp) {
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
            removeColumns() {
                this.$emit('remove')
            },
            removeColumn(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            },
            pickColumnsAction() {
                this.showColumnsEditModal = false
            },
            editColumns() {
                this.showColumnsEditModal = true
            }
        },
        mounted : function() {
            this.setColumnsWidths(this.settings.selectedColumns)
            this.renderCompatibleColumnVariations(this.columns.length)

            if(this.columns.length == 0) {
                this.addColumn()
                this.addColumn()
            }
        }
    }
</script>

<style scoped lang="scss">
    .border {
        border: 1px solid rgba(0,0,0, 0.15);
        padding: 10px;
        text-align: center;
        height: 62px;
    }

    .form-sub-group {
        padding: 8px 12px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .columns-block-body {
    }

    .no-padding {
        padding: 0px;
    }

    .column-bkg {
        background: rgba(255,255,255, 0.01);
        padding: 10px;
        text-align: center;
        border: 1px solid  rgba(0,0,0, 0.15);
    }

    .column-bkg:not(:first-child) {
        border-left: 0;
    }

    .columns {
        margin-bottom: 15px;
    }

    .column-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        // border: 1px solid transparent;
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
        // border: 1px solid transparent;
        cursor: pointer;
        // width: 33.3%;
        flex: 0.15;
        font-size: 10px;
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
        border-right: 1px solid rgba(0,0,0, 0.15);
        margin: 0px;
        padding: 4px;
    }

    .column-item:first-child {
        /*  on all but the first column  */
        margin-left: 0px !important;
    }

    .column-item:last-child {
        /*  on all but the last column  */
        margin-right: 0px !important;
        border-right: 0px;
    }

    // .column-item:not(:first-child) {
    //     border-left: 0;
    // }
</style>
