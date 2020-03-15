<script>
    import ComponentWrapper from './ComponentWrapper'
    import ComponentWrapperFrontend from './ComponentWrapperFrontend'

    export default {
        functional: true,
        render (createElement, context) {
            function getComponent () {
                let mode = context.props.mode
                if (mode == 'frontend') return ComponentWrapperFrontend
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
