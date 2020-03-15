<template>
    <div id="uniqueId+'-markdown'" class="content-block">
        <div v-if="showToolbar" class="markdown-toolbar">
            <i class="lo-icon lo-icon-eye toobar-button" style="cursor: pointer;" @click="markdownPreviewToggle()" title="Preview"></i>
            <i class="lo-icon lo-icon-columns toobar-button" @click="toggleSideBySide()" title="toggle side by side"></i>
        </div>
        <div>
            <textarea v-show="!markdownPreview"
                v-autosize
                :id="uniqueId+'-textarea'"
                :value="content"
                @input="update"
                class="form-control"
                style="float:left; width:50%; background-color: transparent;"
                v-bind:style="{'float': 'left', 'width': markdownEditorWidth }">
                {{ content }}
            </textarea>
            <div class="markdown-preview">
                <div v-show="markdownSideBySide && !markdownPreview"
                    v-html="compiledMarkdown"
                    style="float:left; padding-left: 10px; padding: 10px; width:50%;">
                </div>
                <div style="clear: both;"></div>
                <div v-show="markdownPreview" v-html="compiledMarkdown" style="font-size: 14px;"></div>
            </div>
        </div>

    </div>
</template>
<script>
    import marked from 'marked'
    import autosize from '~/utils/directives/autosize'
    import _ from 'lodash'

    export default {
        directives: { autosize },
        data: function data() {
            return {
                markdownSideBySide: false,
                markdownEditorWidth: '100%',
                markdownPreview: false,
                content: this.value,
            }
        },
        props: {
            uniqueId: {type: String, default: null},
            value: {type: String, default: 'Get creative!'},
            showToolbar: {type: Boolean, default: false},
        },
        watch: {
            // transparentBackground(val, old) {
            //     console.log('transparentBackground: ' + val)
            // },
        },
        computed: {
            compiledMarkdown: function () {
                return marked(this.content, { sanitize: true })
            },
            transparentInputBackground: function () {
                if(this.transparentBackground)
                    return 'rgba(0,0,0, 0.2)'
            }
        },
        methods: {
            markdownPreviewToggle() {
                this.markdownPreview = !this.markdownPreview
            },
            toggleSideBySide() {
                this.markdownSideBySide = !this.markdownSideBySide
                if(this.markdownSideBySide)
                    this.markdownEditorWidth = '50%'
                else
                    this.markdownEditorWidth = '100%'
            },
            // makeBold() {
            //     // var mytextarea = $('#'+this.uniqueId+'-textarea')[0]
            //     // var selectionText = mytextarea.value.substr(mytextarea.selectionStart, mytextarea.selectionEnd)
            //     // mytextarea.value = "**" + selectionText + "**"
            //
            //     this.wrapText(this.uniqueId+'-textarea', "**", "**")
            // },
            // makeItalic() {
            //     this.wrapText(this.uniqueId+'-textarea', "*", "*")
            // },
            // wrapText(elementID, openTag, closeTag) {
            //     var textArea = $('#' + elementID);
            //     var len = textArea.val().length;
            //     var start = textArea[0].selectionStart;
            //     var end = textArea[0].selectionEnd;
            //     var selectedText = textArea.val().substring(start, end);
            //     var replacement = openTag + selectedText + closeTag;
            //     this.content = (textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len))
            // },
            update: _.debounce(function (e) {
                // console.log('updated')
                this.content = e.target.value
                this.$emit('input', this.content)
            }, 100),
            // update(e) {
            //     this.content = e.target.value
            //     this.$emit('input', this.content)
            // }
        },
        mounted : function() {
            // $('html, body').scrollTop( $(document).height() )
        },
    }
</script>
<style scoped lang="scss">

    .markdown-toolbar {
        padding: 2px 10px;
        background: rgba(0,0,0, 0.08);
        border: 1px solid rgba(17, 17, 17, 0.39);
    }

    .toobar-button {
        cursor: pointer;
        text-align: center;
        width: 22px;
        margin-right: 5px;

        display: inline-block;
        text-align: center;
        text-decoration: none!important;
        width: 30px;
        height: 30px;
        margin: 0;
        border: 1px solid transparent;
        border-radius: 3px;
        cursor: pointer;
        line-height: 26px;
        font-size: 15px;

            &:hover {
                // background-color: rgba(0,0,0, 0.25);
                color: #06c;
            }
    }
</style>
