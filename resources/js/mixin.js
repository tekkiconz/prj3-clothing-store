import {format} from 'date-fns';

export default {


    methods: {


        successMessage(data) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: data.status,
                title: data.message
            })
        },

        validationError() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'please fill form correctly',
                // footer: '<a href>Why do I have this issue?</a>'
            });
        }

    },

    mounted() {

        // $('.select2_demo_2').select2();

        // $('.multiselect__input').addClass('form-control');

    },

    filters: {
        formatPrice: function (value) {

            return parseFloat(value).toFixed(2);
        },
        strippedContent: function (string) {
            return string.replace(/<\/?[^>]+>/ig, " ");
        },

        dateToString(datePassed) {

            const newYears = new Date(datePassed);
            const formattedDate = newYears.toDateString().slice(3);
            const valuedate = formattedDate.split(' ');
            // console.log(valuedate);
            return valuedate[1] + ' ' + valuedate[2] + ', ' + valuedate[3];
            // const formattedDate = format(newYears.toDateString(), 'MMM dd, yyyy');
            // return formattedDate;

        }
    }


}
