import Block from './Block'
import Settings from './Settings'

export default {
	block: Block,
	settings: Settings,
	type: 'layout',
	parent: 'columns',
    disallowedList: [
        'container',
        'columns',
        'column',
        'slide'
    ],
	topToolbar: ['addComponents']
}
