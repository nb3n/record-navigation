import HeroSection from '@/components/blocks/hero/hero';
import Header from '@/components/blocks/hero/header';
import type { AvatarList } from '@/components/blocks/hero/hero';

export default function AgencyHeroSection() {
    const avatarList: AvatarList[] = [
        {
            image: 'https://cdn.rnd.nben.com.np/media/profiles/user-1.jpg',
        },
        {
            image: 'https://cdn.rnd.nben.com.np/media/profiles/user-2.jpg',
        },
        {
            image: 'https://cdn.rnd.nben.com.np/media/profiles/user-3.jpg',
        },
        {
            image: 'https://cdn.rnd.nben.com.np/media/profiles/user-5.jpg',
        },
    ];

    return (
        <div className="relative">
            <Header />
            <main>
                <HeroSection avatarList={avatarList} />
            </main>
        </div>
    );
}
