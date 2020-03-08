<template>
    <div>
        <b-container class="mt-2">        
            <b-button href="/configure/report/printPDF">Print PDF</b-button>
            <b-button href="/configure/report/viewPDF">View PDF</b-button>
        </b-container>            
    </div>
</template>

<script>
export default {
    name: "ReportsIndex",
    props: ["host"]
};
</script>
