<template>
    <div class="w-full md:flex md:justify-center p-4 md:p-6 space-x-4 md:space-x-6">
        <div class="max-w-[380px]">
            <div class="border-2 border-gray-200 bg-gray-100 p-2 md:p-4 space-y-2">
                <div class="px-2 space-y-2">

                    <!-- Starting messages -->
                    <h2 class="text-lg">How many pies do you have?</h2>

                    <div class="">Let's calculate them:</div>

                    <input type="text" name="calculation" id="calculation" v-model="calculation" class="border-1 border-gray-300 bg-white md:px-4 px-2 md:py-2 py-1">
                    <div class="text-red-500" v-show="errors">{{ errors }}</div>

                    
                    <!-- Messages back -->
                    <div v-show="result">
                        You have {{ result }} pies.
                    </div>

                    <div class="text-blue-600" v-show="result > 2 && result < 5">
                        You've got a lot of pie there; don't eat it all at once.
                    </div>
                    <div class="text-blue-600" v-show="result >= 5">
                        That's a lot of pies; don't eat them all by yourself.
                    </div>
                </div>
            
                <div class="w-full flex justify-between my-2 space-x-2">
                    <!-- Number buttons -->
                    <table class="border-separate border-spacing-2 w-[200px]">
                        <tbody>
                            <tr>
                                <td v-for="num in [1,2,3]" class="py-1 w-1/3 border-1 border-gray-300 shadow-lg bg-white text-center" @click="addChar(num)">
                                    <button class="text-lg">{{ num }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td v-for="num in [4,5,6]" class="py-1 border-1 border-gray-300 shadow-lg bg-white text-center" @click="addChar(num)">
                                    <button class="text-lg">{{ num }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td v-for="num in [7,8,9]" class="py-1 border-1 border-gray-300 shadow-lg bg-white text-center" @click="addChar(num)">
                                    <button class="text-lg">{{ num }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 border-1 border-gray-300 shadow-lg bg-white text-center" @click="addChar(3.1415927)">
                                    <button class="text-lg">&pi;</button>
                                </td>
                                <td v-for="item in ['0','.']" class="py-1 border-1 border-gray-300 shadow-lg bg-white text-center" @click="addChar(item)">
                                    <button class="text-lg">{{ item }}</button>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Operator buttons -->
                    <table class="border-separate border-spacing-2 w-[150px]">
                        <tbody>
                            <tr>
                                <td v-for="item in ['(',')']" class="bg-gray-200 border-1 border-gray-300 shadow-lg text-center py-1 w-1/2" @click="addChar(item)">
                                    <button class="text-lg">{{ item }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td v-for="item in ['+','-']" class="bg-gray-200 border-1 border-gray-300 shadow-lg text-center py-1" @click="addChar(item)">
                                    <button class="text-lg">{{ item }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td v-for="item in ['*','/']" class="bg-gray-200 border-1 border-gray-300 shadow-lg text-center py-1" @click="addChar(item)">
                                    <button class="text-lg">{{ item }}</button>
                                </td>
                            </tr>
                            <tr class="m-1">
                                <td v-for="item in ['^2','√(']" class="bg-gray-200 border-1 border-gray-300 shadow-lg text-center py-1" @click="addChar(item)">
                                    <button class="text-lg">{{ item }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mx-2 mb-2">
                    <button class="bg-blue-800 shadow-lg p-2 text-xl w-full text-white" @click="calculate">=</button>
                </div>
            </div>

            <div class="text-sm p-4 md:p-6 border-2 border-gray-200 bg-gray-100 md:my-6 my-4">
                <div>Need some ideas? Try these:</div>
                <div class="ml-2">
                    <p>6+3(5-2)</p>
                    <p>((((8+8)^2)))+3*2.5</p>
                    <p>(3^2*2)^2+(3^2*2)^2</p>
                    <p>√(3+6)+5</p>
                    <p>Or see tests/Feature/CalculationTest.php</p>
                </div>
            </div>
        </div>

        <!-- Calculations -->
        <div class="max-w-[380px] min-w-[300px]">
            <div class="border-2 border-gray-200 p-2 md:p-4 mt-4 sm:mt-0 bg-gray-100">
                
                <div class="flex justify-between space-x-4 mb-2">
                    <h2 class="text-lg ">Calculation History</h2>
                    <button class="mt-1" @click="deleteAll()">
                        <div class="">Clear All </div>
                    </button>
                </div>
                
                <div v-for="item in history">
                    <div>{{ item.calculation }}</div>
                    <div class="text-gray-600 text-right mb-2 flex justify-between">
                        {{ item.result }}
                        <Trash :url="'/calculation/' + item.id" @click="deleteItem(item.id)"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import Trash from '../components/Trash.vue';

    const result = ref();
    const calculation = ref('');
    const errors = ref('');
    const history = ref({});

    onMounted(() => {
        calculationHistory();
    });

    function calculate(){
        errors.value = "";
        result.value = "";
        
        axios.post('/calculate', {string: calculation.value})
            .then(response => {
                console.log(response.data);
                result.value = response.data;
                calculationHistory();
            })
            .catch(error => {
                console.log(error);
                errors.value = error.response.data.message;
            })
    }

    function calculationHistory(){
        console.log('getting history');
        axios.get('/api/calculation/history')
                .then(response => {
                    console.log(response.data.data);
                    history.value = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
    }

    function addChar(char){
        let cursorPos = document.getElementById('calculation').selectionStart;
        
        calculation.value = calculation.value.substring(0,cursorPos) + char + calculation.value.substring(cursorPos)
    }

    function deleteItem(itemId){
        console.log('deleting item: ' + itemId);
        axios.delete("/calculation/" + itemId)
                .then(response => {
                    console.log(response);
                    calculationHistory();
                })
                .catch(error => {
                    console.log(error);
                });
    }

    function deleteAll(){
        console.log('deleting all calculations for user');
        axios.get("/calculation/delete_all")
                .then(response => {
                    console.log(response);
                    calculationHistory();
                })
                .catch(error => {
                    console.log(error);
                });
    }
</script>