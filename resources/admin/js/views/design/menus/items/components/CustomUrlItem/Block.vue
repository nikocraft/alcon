<template>
    <div>
        <div v-if="showContent" class="menu-item-settings">
            <div class="form-group">
                <label>Label</label>
                <div class="">
                    <input type="text" class="form-control" v-model="item.title">
                </div>
            </div>
            <div class="form-group">
                <label>Url</label>
                <div class="">
                    <input type="text" class="form-control" v-model="item.url">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data () {
            return {
                item: {
                    label: '',
                    subText: '',
                    icon: '',
                    url: '', // url is stored here...
                },
            }
        },
        computed: {
            blockType() {
                return this.$store.getters['menus/editor/itemType'](this.uniqueId)
            },
            content() {
                return this.$store.getters['menus/editor/itemContent'](this.uniqueId)
            }
        },
        watch: {
            item: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions('menus/editor', [
                'updateItemContent',
            ])
        },
        mounted () {
            if(this.content) {
                this.item = this.content
            }
        }
    }
</script>
