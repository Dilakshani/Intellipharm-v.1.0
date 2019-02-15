<template>
<div>
    <div class="content-header">
        <h3>Heading</h3>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt rounded">
        <ol class="breadcrumb bg-white">
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fa fa-bullhorn"></i> Sub heading
            </li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-md-12 text-right mb-4">
            <router-link to="/newProgram">
                <a class="btn btn-success text-white"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
            </router-link>
        </div>

        <div class="col-md-12">
            <div class="content-panel">
                <h4 class="mb-3"><i class="fa fa-angle-right"></i> List of Employers</h4>
                <!-- Select All records -->
                <input type='button' @click='allRecords()' value='Select All users'>
                <br><br>

                <!-- Select record by ID -->
                <input type='text' v-model='userid' placeholder="Enter Userid between 1 - 24">
                <input type='button' @click='recordByID()' value='Select user by ID'>
                <br><br>

                <!-- List records -->
                <table border='1' width='80%' style='border-collapse: collapse;'>
                    <tr>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Joined Date</th>
                    </tr>

                    <tr v-for='member in members.members' :key="member.id">
                    <td>{{ member.firstname }}</td>
                    <td>{{ member.surname }}</td>
                    <td>{{ member.email }}</td>
                    <td>{{ member.gender }}</td>
                    <td>{{ member.joined_date }}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
</template>

<script>
import { promptMsgMixin } from '../../mixins/prompt_msg.js';

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
            email:"",
            joined_date: "",
            members: {},
            programs: {},
            timezones: []
        };
    },
    created: function() {
        //Load in scripts when page is created!
        //when page load members table is load in the home page.
        this.load_members();

    },
    methods: {

        load_members: function() {
            var _this = this;
            $.get('data/members.json')
                .done(function(data) {
                    _this.members = data;
                });
        },

        load_timezones_list: function() {
            $.get('data/timezones.json')
                .done(function(data) {
                    var list = [];

                    for (var i = 0; i < data.timezones.length; i++) {
                        var id = data.timezones[i].id;
                        var name = data.timezones[i].name;
                        list.push({
                            value: id,
                            name: name
                        });
                    }

                    var classes = document.getElementsByClassName('timezones-list');

                    for (var ci = 0; ci < classes.length; ci++) {
                        classes[ci].dataset.values = JSON.stringify(list);
                    }
                });
        },
    }
}
</script>
