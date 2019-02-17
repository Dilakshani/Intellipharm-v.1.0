<template>
<div>

</div>
</template>

<script>
 // Import jQuery plugin
import $ from 'jquery'
window.jQuery = $;

import {
    promptMsgMixin
} from '../../mixins/prompt_msg.js';

import Vue from 'vue';


var home_programs = {};
var home_programs_entry;

export default {
    mixins: [promptMsgMixin],
    waitForData: true,
    data: function() {
        return {

        };
    },
    created: function() {

    },
    computed: {

    },
    methods: {


    }
}

</script>
