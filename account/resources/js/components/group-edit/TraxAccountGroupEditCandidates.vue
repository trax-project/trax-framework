<template>
    <div>

        <!-- Table -->

        <trax-ui-table-with-actions :id="id" :titles="titles" :actions="actions" header="1">
        </trax-ui-table-with-actions>

        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-group-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>

    </div>
</template>

<script>
    export default {

        props: ['groupId'],

        data: function() {
            return {
                dataTable: null,
                titles: [
                    lang.trax_account.common.email,
                    lang.trax_account.common.fullname,
                    ''
                ],
                props: [
                    {source: this.email, name: 'email'},
                    {source: this.fullname, order: 'lastname'}
                ],
                id: 'trax-account-group-edit-candidates',
                id_members: 'trax-account-group-edit-members',
                endpoint: app_url+'trax/ajax/account/users',
                endpoint_members: app_url+'trax/ajax/account/groups/'+this.groupId+'/users',
                endpoint_toggle: app_url+'trax/ajax/account/groups/'+this.groupId+'/users/toggle',
                actions: [
                    {name: 'toggle', icon: 'label', class: 'btn-default' }
                ],
                members: null,
                bus: this.$bus
           };
        },

        created: function() {
            this.bus.$on(this.id_members+'-unregistered', this.unregistered);
        },
        
        mounted: function() {
            this.initTable();
        },
        
        methods: {
        
            // Columns rendering

            email(data, type, row, meta) {
                return this.active(row, row.email);
            },

            fullname(data, type, row, meta) {
                return this.active(row, row.data.lastname + ' ' + row.data.firstname);
            },

            active(row, content) {
                if (row.active) return content;
                return '<span class="trax-text-muted">' + content + '</span>';
            },

            initTable() {
                var that = this;

                // Initialize datatable

                $.fn.dataTable.ext.errMode = function (settings, help_page_number, message) {
                    console.log('DataTables error: '+message);
                };

                that.dataTable = $(that.$el).find('table').DataTable({
                    serverSide: true,
                    deferRender: true,
                    processing: false,
                    ajax: that.endpoint,
                    columns: that.columnsDef(),
                    language: lang.trax_ui.datatables,
                    lengthChange: false,
                    info: false,
                    createdRow: function( row, data, dataIndex, cell ) {
                        $(row).addClass('trax-row-'+data.id);
                    }
                });

                // Ajax data loaded

                that.dataTable.on('xhr.dt', function () {
                    that.loadMembers();
                } );

                // Change search trigger event (key up > enter)
                
                $('#'+that.id+'-table_filter input[type=search]').unbind();
                $('#'+that.id+'-table_filter input[type=search]').bind('keyup', function(e) {
                    if(e.keyCode == 13) {
                        that.dataTable.search(this.value).draw();   
                    }
                });

                // Buttons event

                $('#'+that.id+'-table tbody').on( 'click', '.btn.toggle', function() {
                    var data = that.dataTable.row( $(this).parents('tr') ).data();
                    that.toggle(data);
                } );
            },

            columnsDef() {
                var res = [];
                for (var index in this.props) {
                    var prop = this.props[index];
                    if (typeof prop.source === "function") {
                        if (prop.order == undefined) {
                            var cell = {data: null, name: prop.name, render: prop.source, orderable: false};
                        } else {
                            var cell = {data: null, name: prop.order, render: prop.source};
                        }
                    } else {
                        var cell = {data: prop.source, name: prop.source, defaultContent: ''};
                    }
                    if (prop.class) cell.className = prop.class;
                    res.push(cell);
                }
                var actionsWith =  (this.actions.length * 40) + 10;
                res.push({ data: null, orderable: false, defaultContent: $('#'+this.id+'-table-actions').html(), className: "text-right pl-0 pr-0 trax-width-"+actionsWith });
                return res;
            },

            loadMembers() {
                var that = this;
                axios.get(that.endpoint_members)
                    .then(function (response) {
                        that.members = response.data.data;
                        that.clearMembers();
                        that.checkMembers();
                    })
            },

            clearMembers() {
                $('#'+this.id+'-table tbody .btn.toggle i').removeClass('text-info');
                //$('#'+this.id+'-table tbody .btn.toggle i').text('label_off');
            },

            checkMembers() {
                for(var index in this.members) {
                    var member = this.members[index];
                    this.checkMember(member.id);
                }
            },

            checkMember(id) {
                $('#'+this.id+'-table tbody .trax-row-'+id+' .btn.toggle i').addClass('text-info');
                //$('#'+this.id+'-table tbody .trax-row-'+id+' .btn.toggle i').text('label');
            },

            uncheckMember(id) {
                $('#'+this.id+'-table tbody .trax-row-'+id+' .btn.toggle i').removeClass('text-info');
                //$('#'+this.id+'-table tbody .trax-row-'+id+' .btn.toggle i').text('label_off');
            },

            toggle(data) {
                var that = this;
                axios.post(this.endpoint_toggle, { 
                        member_id: data.id, 
                    })
                    .then(function (response) {
                        that.bus.$emit(that.id+'-toggled', data);
                        that.loadMembers();
                    })
                    .catch(function (error) {
                        that.bus.$emit('trax-account-group-toastr-error', error.response.data);
                    });
            },

            unregistered(data) {
                this.loadMembers();
            },

            refresh() {
                this.dataTable.ajax.reload(null, true);
            }
        }
    }
</script>
