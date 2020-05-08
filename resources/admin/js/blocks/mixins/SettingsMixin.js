import { mapActions } from 'vuex'

export default {
    props: {
        storePath: {type: String, default: 'content/editor'},
        uniqueId: {type: Number, default: 0},
        ancestorSettings: {type: Object}
    },
    data() {
        return {
            activeTab: 'general',
            optionsDevLanguages: [
                {key: 'php', value: 'PHP'},
                {key: 'javascript', value: 'JavaScript'},
                {key: 'typescript', value: 'TypeScript'},
                {key: 'java', value: 'Java'},
                {key: 'csharp', value: 'C-Sharp'},
                {key: 'go', value: 'Go'},
                {key: 'jsx', value: 'React JSX'},
                {key: 'tsx', value: 'React TSX'},
                {key: 'html', value: 'HTML'},
                {key: 'css', value: 'CSS'},
                {key: 'sass', value: 'Sass'},
                {key: 'scss', value: 'Scss'},
                {key: 'less', value: 'Less'},
                {key: 'html', value: 'XML'},
                {key: 'html', value: 'SVG'},
                {key: 'git', value: 'GIT'},
                {key: 'json', value: 'JSON'},
                {key: 'regex', value: 'Regex'},
                {key: 'markdown', value: 'Markdown'},
                {key: 'graphql', value: 'GraphQL'},
                {key: 'sql', value: 'SQL'},
                {key: 'bash', value: 'Bash'},
                {key: 'docker', value: 'Docker'},
                {key: 'yaml', value: 'Yaml'},
                {key: 'nginx', value: 'Nginx'},
            ],
            optionsJustifyContent: [
                {key: 'flex-start', value: 'Start'},
                {key: 'center', value: 'Center'},
                {key: 'flex-end', value: 'End'},
                {key: 'space-between', value: 'Space Between'},
                {key: 'space-around', value: 'Space Around'},
                {key: 'space-evenly', value: 'Space Evenly'},
            ],
            optionsAlignContent: [
                {key: 'flex-start', value: 'Start'},
                {key: 'center', value: 'Center'},
                {key: 'flex-end', value: 'End'},
                {key: 'stretch', value: 'Stretch'},
                {key: 'space-between', value: 'Space Between'},
                {key: 'space-around', value: 'Space Around'},
            ],
            optionsAlignItems: [
                {key: 'flex-start', value: 'Start'},
                {key: 'center', value: 'Center'},
                {key: 'flex-end', value: 'End'},
                {key: 'baseline', value: 'Baseline'},
                {key: 'stretch', value: 'Stretch'},
            ],
            optionsAlignSelf: [
                {key: 'auto', value: 'Auto'},
                {key: 'flex-start', value: 'Start'},
                {key: 'center', value: 'Center'},
                {key: 'flex-end', value: 'End'},
                {key: 'baseline', value: 'Baseline'},
                {key: 'stretch', value: 'Stretch'},
            ],
            optionsDisplay: [
                {key: 'block', value: 'Block'},
                {key: 'flex', value: 'Flex'},
                {key: 'none', value: 'None'},
            ],
            optionsFlexDirection: [
                {key: 'row', value: 'Row'},
                {key: 'column', value: 'Column'},
                {key: 'row-reverse', value: 'Row Reverse'},
                {key: 'column-reverse', value: 'Column Reverse'},
            ],
            optionsFlexWrap: [
                {key: 'nowrap', value: 'No Wrap'},
                {key: 'wrap', value: 'Wrap'},
                {key: 'wrap-reverse', value: 'Wrap Reverse'},
            ]
        }
    },
    methods: {
        ...mapActions({
            updateItemSettings(dispatch, payload) {
                return dispatch(`${this.storePath}/updateItemSettings`, payload)
            },
            updateItemContent(dispatch, payload) {
                return dispatch(`${this.storePath}/updateItemContent`, payload)
            },
            addItem(dispatch, payload) {
                return dispatch(`${this.storePath}/addItem`, payload)
            },
            removeItem(dispatch, payload) {
                return dispatch(`${this.storePath}/removeItem`, payload)
            },
            updateItemsList(dispatch, payload) {
                return dispatch(`${this.storePath}/updateItemsList`, payload)
            }
        })
    },
    computed: {
        settings() {
            return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
        },
        content: {
            get() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
            set(val) {
                this.updateItemContent({content: val, uniqueId: this.uniqueId})
            }
        }
    },
    watch: {
        settings: {
            handler(val) {
                if(val)
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                else
                    this.$emit('self-cancel')
            },
            deep: true
        }
    }
}
