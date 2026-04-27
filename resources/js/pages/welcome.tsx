import { Head } from '@inertiajs/react';
import AboutUs from '@/components/blocks/about';
import CTA from '@/components/blocks/cta';
import Faq from '@/components/blocks/faq';
import Feature from '@/components/blocks/feature';
import Footer from '@/components/blocks/footer';
import AgencyHeroSection from '@/components/blocks/hero';
import Header from '@/components/blocks/hero/header';

export default function Welcome() {
    return (
        <>
            <Head title="Record Navigation">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
                    rel="stylesheet"
                />
            </Head>

            <div className="relative">
                <Header />
                <AgencyHeroSection />
                <Feature />
                <AboutUs />
                <Faq />
                <CTA />
                <Footer />
            </div>
        </>
    );
}
