import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        uniqueId: {type: Number, default: 0},
        type: {type: String}
    },
    data () {
        return {
            //
        }
    },
    computed: {
        settings () {
            return this.$store.getters.itemSettings(this.uniqueId)
        },
        content: {
            get() {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            set(val) {
                this.updateContent(val)
            }
        }
    },
    watch: {
        settings: {
            handler(val) {
                if (val)
                    this.updateSettings(val)
                else
                    this.$emit('self-cancel')
            },
            deep: true
        }
    },
    methods: {
        ...mapActions([
            'updateItemSettings',
            'updateItemContent'
        ]),
        updateSettings (val) {
            this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
        },
        updateContent (val) {
            this.updateItemContent({content: val, uniqueId: this.uniqueId})
        }
    }
}
