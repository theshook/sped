<template>
    <div>
        <b-container class="mt-2">        
            <b-button href="/report/printPDF">Print PDF</b-button>
        </b-container>            
    </div>
</template>

<script>
export default {
    name: "ReportsIndex",
    props: ["host"]
};
</script>
