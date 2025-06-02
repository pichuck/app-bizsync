<template>
    <section class="page-section login-page">
        <div class="full-width-screen">
            <div class="container-fluid">
                <div class="content-detail">
                    <div class="main-info">
                        <div class="hero-container">
                            <!-- Login form -->
                            <form class="login-form" @submit.prevent="submit">
                                <div
                                    class="imgcontainer flex justify-center items-center"
                                >
                                    <img
                                        src="/assets/images/bizsync-text.png"
                                        style="width: 180px"
                                        alt="Avatar"
                                        class="avatar"
                                    />
                                </div>

                                <div class="input-control">
                                    <input
                                        v-model="form.uname"
                                        type="text"
                                        placeholder="Enter Username or Email"
                                        name="uname"
                                        required
                                    />
                                    <span
                                        v-if="errors.uname"
                                        class="error-message"
                                        >{{ errors.uname }}</span
                                    >

                                    <span class="password-field-show">
                                        <input
                                            v-model="form.password"
                                            type="password"
                                            placeholder="Enter Password"
                                            name="password"
                                            class="password-field"
                                            required
                                        />
                                    </span>
                                    <span
                                        v-if="errors.password"
                                        class="error-message"
                                        >{{ errors.password }}</span
                                    >

                                    <label class="label-container">
                                        Remember me
                                        <input
                                            v-model="form.remember"
                                            type="checkbox"
                                        />
                                        <span class="checkmark"></span>
                                    </label>

                                    <span class="psw">
                                        <a href="#" class="forgot-btn"
                                            >Forgot password?</a
                                        >
                                    </span>

                                    <div class="login-btns">
                                        <button
                                            type="submit"
                                            :disabled="processing"
                                        >
                                            {{
                                                processing
                                                    ? "Logging in..."
                                                    : "Login"
                                            }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import FontComponent from "@/Components/Font.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    name: "LoginPage",
    components: {
        FontComponent,
    },
    props: {
        errors: Object,
    },
    setup() {
        const form = useForm({
            uname: "",
            password: "",
            remember: false,
        });

        const submit = () => {
            form.post("/login");
        };

        return {
            form,
            submit,
            processing: form.processing,
        };
    },
};
</script>

<style scoped>
.error-message {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}
</style>
