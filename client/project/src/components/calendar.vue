<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="mon-sun-btn">
                        <button v-on:click="firstMonday()" class="btn btn-secondary btn-sm">Monday First</button>
                        <button v-on:click="firstSunday()" class="btn btn-secondary btn-sm">Sunday First</button>
                        <button v-on:click="changeFormat12()" class="btn btn-success btn-sm">12 Hours</button>
                        <button v-on:click="changeFormat24()" class="btn btn-success btn-sm">24 Hours</button>
                    </div>
                    <p class="rooms">
                        <button v-for="(room,index) in rooms" v-on:click="selectedRoom(index)"
                                class="btnRoom btn btn-info btn-sm"
                                :class="{selBtn: room.id == selRoom.id}">
                            {{room.name}}
                        </button>
                    </p>
                    <div class="calendar">
                        <header class="header">
                            <button @click="previousMonth"><</button>
                            <h3>{{ currentMonthLabel }} {{ currentYear }}</h3>
                            <button @click="nextMonth">&gt;</button>
                        </header>

                        <div class="headings" v-for="dayLabel in dayLabels">
                            {{ dayLabel }}
                        </div>

                        <div v-for="(day) in daysArray"
                             class="day"
                             :class="dayClassObj(day)">
                            <button>
                                {{ day.date | formatDateToDay }}
                                <div v-for="eventData in events">
                                    <div v-if="convert(day.date) == splitDate(eventData.time_start) &&
                             eventData.id_room == selRoom.id">
                                        <div v-if="show12">
                                            <a style="font-size: xx-small" @click.stop="onClickEventHandler(eventData)">
                                                {{tConvert(splitTime(eventData.time_start))}}
                                                - {{tConvert(splitTime(eventData.time_end))}}
                                            </a>
                                        </div>
                                        <div v-if="!show12">
                                            <a style="font-size: xx-small" @click.stop="onClickEventHandler(eventData)">
                                                {{splitTime(eventData.time_start)}} -  {{splitTime(eventData.time_end)}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div v-if="showWindow == true">
                        <div>
                            <h3>Event Info:</h3>
                            <b-form @submit="onSubmit" @reset="onReset" v-if="show">

                                <b-form-group id="input-group-4" label="Who:" label-for="input-4">
                                    <b-form-input
                                            id="input-4"
                                            v-model="form.username"
                                            required
                                            placeholder="Who"
                                            readonly
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group id="input-group-2" label="Time start:" label-for="input-1">
                                    <b-form-input
                                            id="input-1"
                                            v-model="form.time_start"
                                            required
                                            placeholder="Time Start"
                                            :readonly="form.id_user != id_user_log && id_role != 2"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group id="input-group-2" label="Time end:" label-for="input-1">
                                    <b-form-input
                                            id="input-1"
                                            v-model="form.time_end"
                                            required
                                            placeholder="Time end"
                                            :readonly="form.id_user != id_user_log && id_role != 2"
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group id="input-group-2" label="Notes:" label-for="input-2">
                                    <b-form-input
                                            id="input-2"
                                            v-model="form.description"
                                            required
                                            placeholder="Enter notes"
                                            :readonly="form.id_user != id_user_log && id_role != 2"
                                    ></b-form-input>
                                </b-form-group>

                                <div v-if="form.recurrent && form.id_user == id_user_log || form.recurrent && id_role == 2">
                                    <b-form-group id="input-group-6" >
                                        <b-form-checkbox-group v-model="form.recurrent" id="checkboxes-6" >
                                            <b-form-checkbox v-bind:value="form.recurrent">
                                                Apply to all occurrences?
                                            </b-form-checkbox>
                                        </b-form-checkbox-group>
                                    </b-form-group>
                                </div>

                                <b-form-group id="input-group-2" label="Submitted:" label-for="input-3">
                                    <b-form-input
                                            id="input-3"
                                            v-model="form.submitted"
                                            required
                                            placeholder="submitted"
                                            readonly
                                    ></b-form-input>
                                </b-form-group>
                                <div v-if="form.id_user == id_user_log || id_role == 2">
                                    <b-button type="submit" variant="primary">Update</b-button>
                                    <b-button type="reset" variant="danger">Delete</b-button>
                                </div>
                            </b-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HTTP} from "../main";
    const MONTH_NAMES = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    export default {
        name: 'Calendar',
        data() {
            return {
                today: null,
                currDateCursor: null,
                showWindow: false,
                id_role: '',
                id_user_log: '',
                selectedDate: null,
                FROM_SUN: ['S', 'M', 'T', 'W', 'Th', 'F', 'S'],
                FROM_MON: ['M', 'T', 'W', 'Th', 'F', 'S', 'S'],
                DAY_NAMES: this.FROM_SUN,
                dayLabels: ['S', 'M', 'T', 'W', 'Th', 'F', 'S'],
                rooms: [],
                showModal: true,
                show12: false,
                selRoom: {
                    id: '1',
                    name: ''
                },
                events: [],
                form: {
                    submitted: '',
                    id_user: '',
                    id_parent: '',
                    username: '',
                    description: '',
                    recurrent: '',
                    time_start: '',
                    time_end: '',
                },
                show: true,
                additional: 0
            };
        },
        created() {
            this.today = new Date();
            this.currDateCursor = this.today;
            this.selectedDate = this.today;
            this.getEvents();
            this.getRooms();
            this.id_role = this.$store.getters.ID_ROLE;
            this.id_user_log = this.$store.getters.ID_USER;
        },
        computed: {
            currentMonth() {
                return this.currDateCursor.getMonth();
            },
            currentYear() {
                return this.currDateCursor.getFullYear();
            },
            currentMonthLabel() {
                return MONTH_NAMES[this.currentMonth];
            },
            daysArray() {
                //************************CREATING ARRAY OF CURRENT MONTH DAYS*******************************
                const date = this.currDateCursor;
                const startOfMonth = dateFns.startOfMonth(date);
                const endOfMonth = dateFns.endOfMonth(date);
                const days = dateFns.eachDay(startOfMonth, endOfMonth).map((day) => ({
                    date: day,
                    isToday: dateFns.isToday(day),
                    isCurrentMonth:  dateFns.isSameMonth(new Date(this.currentYear, this.currentMonth), day),
                    isWeekend: dateFns.isWeekend(day),
                    isSelected: dateFns.isSameDay(this.selectedDate, day)
                }));
                //************************* ADD DAYS OF PREV MONTH IN THE BEGINING OF ARRAY DAYS***************
                let previousMonthCursor = dateFns.lastDayOfMonth(dateFns.addMonths(date, -1));
                var begIndex = dateFns.getDay(days[0].date);
                if(this.DAY_NAMES == this.FROM_MON){
                    begIndex = begIndex-1;
                }
                for (let i = begIndex; i > 0; i--) {
                    days.unshift({ //Добавляет элемент в начало массива
                        date: previousMonthCursor,
                        isCurrentMonth: false,
                        isWeekend: dateFns.isWeekend(previousMonthCursor),
                        isSelected: dateFns.isSameDay(this.selectedDate, previousMonthCursor)
                    });
                    previousMonthCursor = dateFns.addDays(previousMonthCursor, -1);
                }
                //************************* ADD DAYS OF NEXT MONTH IN THE END OF ARRAY DAYS******************************************
                const daysNeededAtEnd = (days.length % 7 > 0) ? (7 -(days.length % 7)) : 0;//4
                let nextMonthCursor = dateFns.addMonths(date, 1);
                nextMonthCursor = dateFns.setDate(nextMonthCursor, 1);
                for (let x = 1; x <= daysNeededAtEnd; x++) {//add to the end of array days next month days (4 days)
                    days.push({
                        date: nextMonthCursor,
                        isCurrentMonth: false,
                        isWeekend: dateFns.isWeekend(nextMonthCursor),
                        isSelected: dateFns.isSameDay(this.selectedDate, nextMonthCursor)
                    });
                    nextMonthCursor = dateFns.addDays(nextMonthCursor, 1);
                }
                return days;
            }
        },
        methods: {
            changeFormat12(){
                this.show12 = true;
            },
            changeFormat24(){
                this.show12 = false;
            },
            tConvert(timeString) {
                let H = +timeString.substr(0, 2);
                let h = (H % 12) || 12;
                let ampm = H < 12 ? "AM" : "PM";
                return timeString = h + timeString.substr(2, 3) + ampm;
            },
            onClickEventHandler(eventData){
                this.form.id_event = eventData.id;
                this.form.id_user = eventData.id_user;
                this.form.username = eventData.username;
                this.form.id_room = eventData.id_room;
                this.form.description = eventData.description;
                this.form.submitted = eventData.create_time;
                this.form.time_start = this.splitTime(eventData.time_start);
                this.form.data_start = this.splitDate(eventData.time_start);
                this.form.time_end = this.splitTime(eventData.time_end);
                this.form.data_end = this.splitDate(eventData.time_end);
                this.form.recurrent = eventData.id_parent;
                this.showWindow = true;
            },
            updateEvent(){
                HTTP.post('event/update/', {
                    jwt: localStorage.getItem('token'),
                    id_event: this.form.id_event,
                    id_user: this.form.id_user,
                    id_room: this.form.id_room,
                    description: this.form.description,
                    time_start: this.form.data_start+' '+this.form.time_start,
                    time_end: this.form.data_end+' '+this.form.time_end,
                    recurrent: this.form.recurrent
                },{headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(response => {
                        if(response.status===200){
                            alert(response.data.status_message)
                        }
                    }).catch(error => {
                    if(error.response){
                        alert(error.response.data.status_message)
                    }
                })
            },
            deleteEvent(){
                if (confirm('Are you sure you want to delete this event: id '+this.form.time_start+' - '+this.form.time_end +'?')) {
                    HTTP.post('event/delete/', {
                        jwt: localStorage.getItem('token'),
                        id: this.form.id_event,
                        recurrent: this.form.recurrent
                    },{headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        }})
                        .then(response => {
                            if(response.status===200){
                                alert(response.data.status_message)
                            }
                        }).catch(error => {
                        if(error.response){
                            alert(error.response.data.status_message)
                        }
                    })
                }
            },
            convert(str){
                let date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            splitDate(str){
                let array=str.split(" ");
                return array[0]
            },
            splitTime(str){
                let array=str.split(" ");
                return array[1]
            },
            getEvents(){
                HTTP.post('event/events/', {
                    jwt: localStorage.getItem('token')
                },{headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(res => {
                        this.events = res.data.data.events
                    }).catch(error => {
                    console.log('error', error);
                })
            },
            getRooms(){
                HTTP.post('room/show/', {
                    jwt: localStorage.getItem('token')
                },{headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }})
                    .then(res => {
                        this.rooms = res.data.data.rooms
                    }).catch(error => {
                    console.log('error', error);
                })
            },
            selectedRoom(index){
                this.selRoom = this.rooms[index]
            },
            firstMonday(){
                this.DAY_NAMES = this.FROM_MON;
                this.dayLabels = this.FROM_MON;
            },
            firstSunday(){
                this.DAY_NAMES = this.FROM_SUN;
                this.dayLabels = this.FROM_SUN;
            },
            dayClassObj(day) { //add  dynamically class to css depending on
                return {
                    'today' : day.isToday,
                    'current': day.isCurrentMonth,
                    'weekend': day.isWeekend,
                    'selected': day.isSelected,
                };
            },
            nextMonth() {
                this.currDateCursor = dateFns.addMonths(this.currDateCursor, 1);
            },
            previousMonth() {
                this.currDateCursor = dateFns.addMonths(this.currDateCursor, -1);
            },
            onSubmit(evt) {
                evt.preventDefault()
                // alert(JSON.stringify(this.form))
                this.updateEvent()
                this.showWindow = false
                setTimeout( () => this.getEvents(), 300)
            },
            onReset(evt) {
                evt.preventDefault()
                this.deleteEvent()
                this.showWindow = false
                setTimeout( () => this.getEvents(), 300)
            }
        },
        //************* FILTER DAYS INFO {date: Sun Mar 31 2019 00:00:00 GMT+0200} TO THIS FORMAT 31 *********************
        filters: {
            formatDateToDay(val) {
                return dateFns.format(val, 'D');
            },
            formatDateToDayMY(val) {
                return dateFns.format(val, '\'MM-DD-YYYY\'');
            }
        },
    }
</script>

<style scoped>
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        width: 650px;
        background-color: #ededf7;
    }
    .calendar > .header {
        padding: .75rem;
        font-size: 1.25rem;
        grid-column: 1 / span 7;
        color: #24524A;
    }
    .calendar > .header button {
        border-radius: 40px;
        border: none;
        background-color: #ededf7;
        margin: 0 1rem;
        padding: .25rem .5rem;
    }
    .calendar > .headings {
        background-color: #5FBB90;
        padding: 10px;
    }
    .calendar > * {
        align-items: center;
        display: flex;
        justify-content: center;
    }
    .calendar > .day {
        color: grey;
        font-size: 1rem;
    }
    .calendar > .day.current {
        color: black;
    }
    .calendar > .day.current.today {
        border-radius: 40px;
        background-color: #FCCDB8;
    }
    .calendar > .day.weekend {
        background-color: #e6e6f4;
    //color: #24524A;
    }
    .calendar > .day::before {
        content: "";
        padding-bottom: 100%;
    }
    .text-center {
        text-align: center;
    }
    .calendar > .day.selected {
        background-color: #5FBB90;
    }
    .calendar > .day button {
        color: inherit;
        background: transparent;
        border: none;
        height: 100%;
        width: 100%;
    }
    .selBtn{
        color: deeppink;
    }
</style>
<!--<template>-->
<!--    <div>-->
<!--      <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-sm-8">-->
<!--              <div class="mon-sun-btn">-->
<!--                <button v-on:click="firstMonday()" class="btn btn-secondary btn-sm">Monday First</button>-->
<!--                <button v-on:click="firstSunday()" class="btn btn-secondary btn-sm">Sunday First</button>-->
<!--                <button v-on:click="changeFormat12()" class="btn btn-success btn-sm">12 Hours</button>-->
<!--                <button v-on:click="changeFormat24()" class="btn btn-success btn-sm">24 Hours</button>-->
<!--             </div>-->
<!--             <p class="rooms">-->
<!--                <button v-for="(room,index) in rooms" v-on:click="selectedRoom(index)"-->
<!--                        class="btnRoom btn btn-info btn-sm"-->
<!--                        :class="{selBtn: room.id == selRoom.id}">-->
<!--                 {{room.name}}-->
<!--                </button>-->
<!--             </p>-->
<!--            <div class="calendar">-->
<!--                <header class="header">-->
<!--                    <button @click="previousMonth"><</button>-->
<!--                    <h3>{{ currentMonthLabel }} {{ currentYear }}</h3>-->
<!--                    <button @click="nextMonth">&gt;</button>-->
<!--                </header>-->

<!--                <div class="headings" v-for="dayLabel in dayLabels">-->
<!--                    {{ dayLabel }}-->
<!--                </div>-->

<!--                <div v-for="(day) in daysArray"-->
<!--                     class="day"-->
<!--                     :class="dayClassObj(day)">-->
<!--                    <button>-->
<!--                    {{ day.date | formatDateToDay }}-->
<!--                        <div v-for="eventData in events">-->
<!--                            <div v-if="convert(day.date) == splitDate(eventData.time_start) &&-->
<!--                             eventData.id_room == selRoom.id">-->
<!--                                <div v-if="show12">-->
<!--                                    <a style="font-size: xx-small" @click.stop="onClickEventHandler(eventData)">-->
<!--                                        {{tConvert(splitTime(eventData.time_start))}}-->
<!--                                        - {{tConvert(splitTime(eventData.time_end))}}-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                                <div v-if="!show12">-->
<!--                                    <a style="font-size: xx-small" @click.stop="onClickEventHandler(eventData)">-->
<!--                                        {{splitTime(eventData.time_start)}} -  {{splitTime(eventData.time_end)}}-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--            </div>-->
<!--                <div class="col-sm-4">-->
<!--                <div v-if="showWindow == true">-->
<!--                        <div>-->
<!--                            <h3>Event Info:</h3>-->
<!--                            <b-form @submit="onSubmit" @reset="onReset" v-if="show">-->

<!--                            <b-form-group id="input-group-4" label="Who:" label-for="input-4">-->
<!--                                <b-form-input-->
<!--                                        id="input-4"-->
<!--                                        v-model="form.username"-->
<!--                                        required-->
<!--                                        placeholder="Who"-->
<!--                                        readonly-->
<!--                                ></b-form-input>-->
<!--                            </b-form-group>-->

<!--&lt;!&ndash;                            <b-form-select v-model="selected" :options="options" :select-size="1"></b-form-select>&ndash;&gt;-->
<!--&lt;!&ndash;                            <div class="mt-3">Selected: <strong>{{ selected }}</strong></div>&ndash;&gt;-->

<!--                            <b-form-group id="input-group-2" label="Time start:" label-for="input-1">-->
<!--                                <b-form-input-->
<!--                                id="input-1"-->
<!--                                v-model="form.time_start"-->
<!--                                required-->
<!--                                placeholder="Time Start"-->
<!--                                :readonly="form.id_user != id_user_log && id_role != 2"-->
<!--                                ></b-form-input>-->
<!--                            </b-form-group>-->

<!--                            <b-form-group id="input-group-2" label="Time end:" label-for="input-1">-->
<!--                                <b-form-input-->
<!--                                        id="input-1"-->
<!--                                        v-model="form.time_end"-->
<!--                                        required-->
<!--                                        placeholder="Time end"-->
<!--                                        :readonly="form.id_user != id_user_log && id_role != 2"-->
<!--                                ></b-form-input>-->
<!--                            </b-form-group>-->

<!--                             <b-form-group id="input-group-2" label="Notes:" label-for="input-2">-->
<!--                                <b-form-input-->
<!--                                id="input-2"-->
<!--                                v-model="form.description"-->
<!--                                required-->
<!--                                placeholder="Enter notes"-->
<!--                                :readonly="form.id_user != id_user_log && id_role != 2"-->
<!--                                ></b-form-input>-->
<!--                            </b-form-group>-->

<!--                            <div v-if="form.recurrent && form.id_user == id_user_log || form.recurrent && id_role == 2">-->
<!--                                <b-form-group id="input-group-6" >-->
<!--                                    <b-form-checkbox-group v-model="form.recurrent" id="checkboxes-6" >-->
<!--                                        <b-form-checkbox v-bind:value="form.recurrent">-->
<!--                                            Apply to all occurrences?-->
<!--                                        </b-form-checkbox>-->
<!--                                    </b-form-checkbox-group>-->
<!--                                </b-form-group>-->
<!--                            </div>-->

<!--                            <b-form-group id="input-group-2" label="Submitted:" label-for="input-3">-->
<!--                                <b-form-input-->
<!--                                id="input-3"-->
<!--                                v-model="form.submitted"-->
<!--                                required-->
<!--                                placeholder="submitted"-->
<!--                                readonly-->
<!--                                ></b-form-input>-->
<!--                            </b-form-group>-->
<!--                                <div v-if="form.id_user == id_user_log || id_role == 2">-->
<!--                                    <b-button type="submit" variant="primary">Update</b-button>-->
<!--                                    <b-button type="reset" variant="danger">Delete</b-button>-->
<!--                                </div>-->
<!--                            </b-form>-->
<!--                        </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--    import {HTTP} from "../main";-->
<!--    const MONTH_NAMES = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];-->

<!--    export default {-->
<!--        name: 'Calendar',-->
<!--        data() {-->
<!--            return {-->
<!--                // selected: null,-->
<!--                // options: [-->
<!--                //     { value: null, text: 'Please select some item' },-->
<!--                //     { value: 'a', text: 'This is option a' },-->
<!--                //     { value: 'b', text: 'Default Selected Option b' },-->
<!--                //     { value: 'c', text: 'This is option c' },-->
<!--                //     { value: 'e', text: 'This is option e' },-->
<!--                //     { value: 'e', text: 'This is option f' }-->
<!--                // ],-->

<!--                // users: [],-->

<!--                today: null,-->
<!--                currDateCursor: null,-->
<!--                showWindow: false,-->
<!--                id_role: '',-->
<!--                id_user_log: '',-->
<!--                selectedDate: null,-->
<!--                FROM_SUN: ['S', 'M', 'T', 'W', 'Th', 'F', 'S'],-->
<!--                FROM_MON: ['M', 'T', 'W', 'Th', 'F', 'S', 'S'],-->
<!--                DAY_NAMES: this.FROM_SUN,-->
<!--                dayLabels: ['S', 'M', 'T', 'W', 'Th', 'F', 'S'],-->
<!--                rooms: [],-->
<!--                showModal: true,-->
<!--                show12: false,-->
<!--                selRoom: {-->
<!--                    id: '1',-->
<!--                    name: ''-->
<!--                },-->
<!--                events: [],-->
<!--                form: {-->
<!--                    submitted: '',-->
<!--                    id_parent: '',-->
<!--                    username: '',-->
<!--                    description: '',-->
<!--                    recurrent: '',-->
<!--                    time_start: '',-->
<!--                    time_end: '',-->
<!--                },-->
<!--                show: true,-->
<!--                additional: 0-->
<!--            };-->
<!--        },-->

<!--        created() {-->
<!--            this.today = new Date();-->
<!--            this.currDateCursor = this.today;-->
<!--            this.selectedDate = this.today;-->
<!--            this.getEvents();-->
<!--            this.getRooms();-->
<!--            this.getUsers();-->
<!--            this.id_role = this.$store.getters.ID_ROLE;-->
<!--            this.id_user_log = this.$store.getters.ID_USER;-->
<!--        },-->

<!--        computed: {-->

<!--            currentMonth() {-->
<!--                return this.currDateCursor.getMonth();-->
<!--            },-->

<!--            currentYear() {-->
<!--                return this.currDateCursor.getFullYear();-->
<!--            },-->

<!--            currentMonthLabel() {-->
<!--                return MONTH_NAMES[this.currentMonth];-->
<!--            },-->

<!--            daysArray() {-->
<!--                //************************CREATING ARRAY OF CURRENT MONTH DAYS*******************************-->
<!--                const date = this.currDateCursor;-->
<!--                const startOfMonth = dateFns.startOfMonth(date);-->
<!--                const endOfMonth = dateFns.endOfMonth(date);-->

<!--                const days = dateFns.eachDay(startOfMonth, endOfMonth).map((day) => ({-->
<!--                    date: day,-->
<!--                    isToday: dateFns.isToday(day),-->
<!--                    isCurrentMonth:  dateFns.isSameMonth(new Date(this.currentYear, this.currentMonth), day),-->
<!--                    isWeekend: dateFns.isWeekend(day),-->
<!--                    isSelected: dateFns.isSameDay(this.selectedDate, day)-->
<!--                }));-->

<!--                //************************* ADD DAYS OF PREV MONTH IN THE BEGINING OF ARRAY DAYS***************-->
<!--                let previousMonthCursor = dateFns.lastDayOfMonth(dateFns.addMonths(date, -1));-->
<!--                var begIndex = dateFns.getDay(days[0].date);-->

<!--                if(this.DAY_NAMES == this.FROM_MON){-->
<!--                    begIndex = begIndex-1;-->
<!--                }-->

<!--                for (let i = begIndex; i > 0; i&#45;&#45;) {-->
<!--                    days.unshift({ //Добавляет элемент в начало массива-->
<!--                        date: previousMonthCursor,-->
<!--                        isCurrentMonth: false,-->
<!--                        isWeekend: dateFns.isWeekend(previousMonthCursor),-->
<!--                        isSelected: dateFns.isSameDay(this.selectedDate, previousMonthCursor)-->
<!--                    });-->
<!--                    previousMonthCursor = dateFns.addDays(previousMonthCursor, -1);-->
<!--                }-->

<!--                //************************* ADD DAYS OF NEXT MONTH IN THE END OF ARRAY DAYS******************************************-->

<!--                const daysNeededAtEnd = (days.length % 7 > 0) ? (7 -(days.length % 7)) : 0;//4-->
<!--                let nextMonthCursor = dateFns.addMonths(date, 1);-->
<!--                nextMonthCursor = dateFns.setDate(nextMonthCursor, 1);-->

<!--                for (let x = 1; x <= daysNeededAtEnd; x++) {//add to the end of array days next month days (4 days)-->
<!--                    days.push({-->
<!--                        date: nextMonthCursor,-->
<!--                        isCurrentMonth: false,-->
<!--                        isWeekend: dateFns.isWeekend(nextMonthCursor),-->
<!--                        isSelected: dateFns.isSameDay(this.selectedDate, nextMonthCursor)-->
<!--                    });-->
<!--                    nextMonthCursor = dateFns.addDays(nextMonthCursor, 1);-->
<!--                }-->
<!--                return days;-->
<!--            }-->
<!--        },-->

<!--        methods: {-->

<!--            changeFormat12(){-->
<!--                this.show12 = true;-->
<!--            },-->

<!--            changeFormat24(){-->
<!--                this.show12 = false;-->
<!--            },-->

<!--            tConvert(timeString) {-->
<!--                let H = +timeString.substr(0, 2);-->
<!--                let h = (H % 12) || 12;-->
<!--                let ampm = H < 12 ? "AM" : "PM";-->
<!--                return timeString = h + timeString.substr(2, 3) + ampm;-->
<!--            },-->

<!--            onClickEventHandler(eventData){-->
<!--                this.form.id_event = eventData.id;-->
<!--                this.form.id_user = eventData.id_user;-->
<!--                this.form.username = eventData.username;-->
<!--                this.form.id_room = eventData.id_room;-->
<!--                this.form.description = eventData.description;-->
<!--                this.form.submitted = eventData.create_time;-->
<!--                this.form.time_start = this.splitTime(eventData.time_start);-->
<!--                this.form.data_start = this.splitDate(eventData.time_start);-->
<!--                this.form.time_end = this.splitTime(eventData.time_end);-->
<!--                this.form.data_end = this.splitDate(eventData.time_end);-->
<!--                this.form.recurrent = eventData.id_parent;-->
<!--                this.showWindow = true;-->
<!--            },-->

<!--            updateEvent(){-->
<!--                HTTP.post('event/update/', {-->
<!--                    jwt: localStorage.getItem('token'),-->
<!--                    id_event: this.form.id_event,-->
<!--                    id_user: this.form.id_user,-->
<!--                    id_room: this.form.id_room,-->
<!--                    description: this.form.description,-->
<!--                    time_start: this.form.data_start+' '+this.form.time_start,-->
<!--                    time_end: this.form.data_end+' '+this.form.time_end,-->
<!--                    recurrent: this.form.recurrent-->
<!--                },{headers: {-->
<!--                        'Accept': 'application/json',-->
<!--                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'-->
<!--                    }})-->
<!--                    .then(response => {-->
<!--                        if(response.status===200){-->
<!--                            alert(response.data.status_message)-->
<!--                        }-->
<!--                    }).catch(error => {-->
<!--                    if(error.response){-->
<!--                        alert(error.response.data.status_message)-->
<!--                    }-->
<!--                })-->
<!--            },-->

<!--            deleteEvent(){-->
<!--                if (confirm('Are you sure you want to delete this event: id '+this.form.time_start+' - '+this.form.time_end +'?')) {-->
<!--                    HTTP.post('event/delete/', {-->
<!--                        jwt: localStorage.getItem('token'),-->
<!--                        id: this.form.id_event,-->
<!--                        recurrent: this.form.recurrent-->
<!--                    },{headers: {-->
<!--                            'Accept': 'application/json',-->
<!--                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'-->
<!--                        }})-->
<!--                        .then(response => {-->
<!--                            if(response.status===200){-->
<!--                                alert(response.data.status_message)-->
<!--                            }-->
<!--                        }).catch(error => {-->
<!--                        if(error.response){-->
<!--                            alert(error.response.data.status_message)-->
<!--                        }-->
<!--                    })-->
<!--                }-->
<!--            },-->

<!--            convert(str){-->
<!--                let date = new Date(str),-->
<!--                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),-->
<!--                    day = ("0" + date.getDate()).slice(-2);-->
<!--                return [date.getFullYear(), mnth, day].join("-");-->
<!--            },-->

<!--            splitDate(str){-->
<!--                let array=str.split(" ");-->
<!--                return array[0]-->
<!--            },-->

<!--            splitTime(str){-->
<!--                let array=str.split(" ");-->
<!--                return array[1]-->
<!--            },-->

<!--            getEvents(){-->
<!--                HTTP.post('event/events/', {-->
<!--                    jwt: localStorage.getItem('token')-->
<!--                },{headers: {-->
<!--                        'Accept': 'application/json',-->
<!--                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'-->
<!--                    }})-->
<!--                    .then(res => {-->
<!--                    this.events = res.data.data.events-->
<!--                }).catch(error => {-->
<!--                    console.log('error', error);-->
<!--                })-->
<!--            },-->

<!--            getRooms(){-->
<!--                HTTP.post('room/show/', {-->
<!--                    jwt: localStorage.getItem('token')-->
<!--                },{headers: {-->
<!--                        'Accept': 'application/json',-->
<!--                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'-->
<!--                    }})-->
<!--                    .then(res => {-->
<!--                        this.rooms = res.data.data.rooms-->
<!--                    }).catch(error => {-->
<!--                    console.log('error', error);-->
<!--                })-->
<!--            },-->

<!--            // getUsers(){-->
<!--            //     HTTP.post('user/show/', {-->
<!--            //             jwt: localStorage.getItem('token')-->
<!--            //         },-->
<!--            //         {headers: {-->
<!--            //                 'Accept': 'application/json',-->
<!--            //                 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'-->
<!--            //             }})-->
<!--            //         .then(res => {-->
<!--            //             console.log(res.data.data.users);-->
<!--            //             this.users = res.data.data.users-->
<!--            //         }).catch(error => {-->
<!--            //         console.log('error', error);-->
<!--            //     })-->
<!--            //-->
<!--            //     this. options = [-->
<!--            //-->
<!--            //         { value: null, text: 'Please select some item' },-->
<!--            //         { value: 'a', text: 'This is option a' },-->
<!--            //     ]-->
<!--            // },-->

<!--            selectedRoom(index){-->
<!--               this.selRoom = this.rooms[index]-->
<!--            },-->

<!--            firstMonday(){-->
<!--                this.DAY_NAMES = this.FROM_MON;-->
<!--                this.dayLabels = this.FROM_MON;-->
<!--            },-->

<!--            firstSunday(){-->
<!--                this.DAY_NAMES = this.FROM_SUN;-->
<!--                this.dayLabels = this.FROM_SUN;-->
<!--            },-->

<!--            dayClassObj(day) { //add  dynamically class to css depending on-->
<!--                return {-->
<!--                    'today' : day.isToday,-->
<!--                    'current': day.isCurrentMonth,-->
<!--                    'weekend': day.isWeekend,-->
<!--                    'selected': day.isSelected,-->
<!--                };-->
<!--            },-->

<!--            nextMonth() {-->
<!--                this.currDateCursor = dateFns.addMonths(this.currDateCursor, 1);-->
<!--            },-->

<!--            previousMonth() {-->
<!--                this.currDateCursor = dateFns.addMonths(this.currDateCursor, -1);-->
<!--            },-->

<!--            onSubmit(evt) {-->
<!--                evt.preventDefault()-->
<!--                // alert(JSON.stringify(this.form))-->
<!--                this.updateEvent()-->
<!--                this.showWindow = false-->
<!--                setTimeout( () => this.getEvents(), 300)-->
<!--            },-->

<!--            onReset(evt) {-->
<!--                evt.preventDefault()-->
<!--                this.deleteEvent()-->
<!--                this.showWindow = false-->
<!--                setTimeout( () => this.getEvents(), 300)-->
<!--          }-->
<!--        },-->
<!--        //************* FILTER DAYS INFO {date: Sun Mar 31 2019 00:00:00 GMT+0200} TO THIS FORMAT 31 *********************-->
<!--        filters: {-->
<!--            formatDateToDay(val) {-->
<!--                return dateFns.format(val, 'D');-->
<!--            },-->
<!--            formatDateToDayMY(val) {-->
<!--                return dateFns.format(val, '\'MM-DD-YYYY\'');-->
<!--            }-->
<!--        },-->
<!--    }-->
<!--</script>-->

<!--<style scoped>-->
<!--    .calendar {-->
<!--        display: grid;-->
<!--        grid-template-columns: repeat(7, 1fr);-->
<!--        width: 650px;-->
<!--        background-color: #ededf7;-->
<!--    }-->
<!--    .calendar > .header {-->
<!--        padding: .75rem;-->
<!--        font-size: 1.25rem;-->
<!--        grid-column: 1 / span 7;-->
<!--        color: #24524A;-->
<!--    }-->

<!--    .calendar > .header button {-->
<!--        border-radius: 40px;-->
<!--        border: none;-->
<!--        background-color: #ededf7;-->

<!--        margin: 0 1rem;-->
<!--        padding: .25rem .5rem;-->
<!--    }-->

<!--    .calendar > .headings {-->
<!--        background-color: #5FBB90;-->
<!--        padding: 10px;-->
<!--    }-->

<!--    .calendar > * {-->
<!--        align-items: center;-->
<!--        display: flex;-->
<!--        justify-content: center;-->
<!--    }-->

<!--    .calendar > .day {-->
<!--        color: grey;-->
<!--        font-size: 1rem;-->
<!--    }-->

<!--    .calendar > .day.current {-->
<!--        color: black;-->
<!--    }-->

<!--    .calendar > .day.current.today {-->
<!--        border-radius: 40px;-->
<!--        background-color: #FCCDB8;-->
<!--    }-->

<!--    .calendar > .day.weekend {-->
<!--        background-color: #e6e6f4;-->
<!--    //color: #24524A;-->
<!--    }-->

<!--    .calendar > .day::before {-->
<!--        content: "";-->
<!--        padding-bottom: 100%;-->
<!--    }-->

<!--    .text-center {-->
<!--        text-align: center;-->
<!--    }-->

<!--    .calendar > .day.selected {-->
<!--        background-color: #5FBB90;-->
<!--    }-->

<!--    .calendar > .day button {-->
<!--        color: inherit;-->
<!--        background: transparent;-->
<!--        border: none;-->
<!--        height: 100%;-->
<!--        width: 100%;-->
<!--    }-->

<!--    .selBtn{-->
<!--        color: deeppink;-->
<!--    }-->
<!--</style>-->