<script>
import {Line} from "vue-chartjs";

export default {
    extends: Line,
    data() {
        return {
            // gradient: null,'dfg','try','er','hjh' 35,64,34,75
            pay_date: [],
            pay_amount: [],
            gradient2: null,
        };
    },
    mounted() {
        this.getPaidData();
        this.setupStyle();
    },

    methods: {

        setupStyle() {

            this.gradient2 = this.$refs.canvas
                .getContext("2d")
                .createLinearGradient(0, 0, 0, 450);

            this.gradient2.addColorStop(0, "#345B2C");
            this.gradient2.addColorStop(0.5, "rgba(52, 91, 44, 0.7)");
            this.gradient2.addColorStop(1, "#345B2C");

        },
        generateChart() {
            this.renderChart(
                {
                    labels: this.pay_date,
                    datasets: [
                        {
                            label: "Current Month Sales",
                            borderColor: "#05CBE1",
                            pointBackgroundColor: "white",
                            pointBorderColor: "black",
                            borderWidth: 1,
                            backgroundColor: this.gradient2,
                            data: this.pay_amount,
                        }
                    ]
                },
                {responsive: true, maintainAspectRatio: false}
            );

        },

        getPaidData() {
            axios.get(base_url + 'admin/sales-amount/chart')
                .then(response => {
                    response.data.map((value, index) => {
                        this.pay_amount.push(Number(value.amount).toFixed(2));
                        this.pay_date.push(value.payment_date);
                    });
                    this.generateChart();
                });
        }
    },
}
</script>
