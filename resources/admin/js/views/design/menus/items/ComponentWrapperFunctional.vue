<script>
    import ComponentWrapper from './ComponentWrapper'

    export default {
        functional: true,
        render (createElement, context) {
            function getComponent () {
                let mode = context.props.mode
                return ComponentWrapper
            }

            return createElement(
                getComponent(),
                context.data,
                context.children
            )
        }
    }
</script>
