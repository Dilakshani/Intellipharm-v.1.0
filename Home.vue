<template>
<div>
    <div class="content-header">
        <h3>Intellipharm Employee Details</h3>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt rounded">
        <ol class="breadcrumb bg-white">
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fa fa-bullhorn"></i> Intellipharm reports
            </li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-md-12">
            <div class="content-panel">
                <!-- List records -->
                <div class="row">
                    <div class="col-lg-8">
                        <h3>Employee list</h3>
                    </div>

                </div>
                <table class="table table-bordered table-striped" id="employee_table">
                    <thead>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Joined Date</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</template>

<script>
 // Import jQuery plugin
import $ from 'jquery'
window.jQuery = $;

import {
    promptMsgMixin
} from '../../mixins/prompt_msg.js';

import Datatable from 'datatables';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vue from 'vue';

import Chart from 'chart.js';

// import { ClientTable } from 'vue-tables-2';

// Vue.use(ClientTable);
Vue.use(VueAxios, axios);

var home_programs = {};
var home_programs_entry;

export default {
    mixins: [promptMsgMixin],
    waitForData: true,
    data: function() {
        return {
            users: "",
            userid: 0,
            firstname: "",
            email: "",
            joined_date: "",
            members: {},
            pagination: {},
            programs: {},
            timezones: [],
        };
    },
    created: function() {
        //Load in scripts when page is created!
        //when page load members table is load in the home page.
        this.load_members();

    },
    computed: {

    },
    methods: {

        load_members: function() {
            var _this = this;
            $.get('data/members.json')
                .done(function(data) {
                    _this.members = data;
                });
        },

        total_pages: function() {

            var members = this.members.members;

            for (var i = 0; i < members.length; i++) {
                this.total = Math.ceil(
                    members[i].length / this.rowsPerPage
                );
            }

            if (this.total < 1) {
                this.total = 1;
            }

            var pagination_array = [];
            for (var x = 1; x <= this.total; x++) {
                pagination_array.push(x);
            }

            this.pagination = pagination_array;

        },
        change_page: function(page) {
            this.startRow = (page * this.rowsPerPage) - this.rowsPerPage;
        },
    }
}

// Generate table using json data.
    window.jQuery(document).ready(function() {
        var result = (function () {
            var json = null;
                window.jQuery .ajax({
                    'async': false,
                    'global': false,
                    'url': "http://localhost:8000/data/members.json",
                    'dataType': "json",
                    'success': function (data) {
                        json = data;
                    }
                });
                return json;
            })();
                window.jQuery ('#employee_table').dataTable( {
                columns : [
                    {data: "firstname"},
                    {data: "surname"},
                    {data: "email"},
                    {data: "gender"},
                    {data: "joined_date"}
                ],
                    data: result
                } );

            });
</script>
